<?php

namespace webignition\CssValidatorOutput\Parser;

use webignition\CssValidatorOutput\Model\AbstractMessage;
use webignition\CssValidatorOutput\Model\ErrorMessage;
use webignition\CssValidatorOutput\Model\IncorrectUsageOutput;
use webignition\CssValidatorOutput\Model\MessageFactory;
use webignition\CssValidatorOutput\Model\MessageList;
use webignition\CssValidatorOutput\Model\ObservationResponse;
use webignition\CssValidatorOutput\Model\OutputInterface;
use webignition\CssValidatorOutput\Model\ValidationOutput;
use webignition\Url\Host\Host;
use webignition\Url\Url;

class OutputParser
{
    /**
     * @param string $validatorOutput
     * @param Configuration $configuration
     *
     * @return OutputInterface
     *
     * @throws InvalidValidatorOutputException
     */
    public function parse(string $validatorOutput, Configuration $configuration): OutputInterface
    {
        $sanitizer = new Sanitizer();
        $validatorOutput = trim($sanitizer->getSanitizedOutput($validatorOutput));

        $headerBodyParts = explode("\n", $validatorOutput, 2);
        $header = trim($headerBodyParts[0]);
        $body = trim($headerBodyParts[1]);

        if (ExceptionOutputParser::is($body)) {
            return ExceptionOutputParser::parse($body);
        }

        if ($this->isIncorrectUsageOutput($header)) {
            return new IncorrectUsageOutput();
        }

        $bodyXmlContent = $this->extractXmlContentFromBody($body);
        if (null === $bodyXmlContent) {
            throw new InvalidValidatorOutputException($validatorOutput);
        }

        $optionsParser = new OptionsParser();
        $options = $optionsParser->parse($header);

        $bodyDom = new \DOMDocument();
        $bodyDom->loadXML($bodyXmlContent);

        $observationResponseElement = $bodyDom->getElementsByTagName('observationresponse')->item(0);

        $sourceUrl = $observationResponseElement->getAttribute('ref');
        $dateTime = $this->createObservationResponseDateTime($observationResponseElement);

        $messageList = new MessageList();
        $observationResponse = new ObservationResponse($sourceUrl, $dateTime, $messageList);
        $output = new ValidationOutput($options, $observationResponse);

        if ($this->isPassedNoMessagesOutput($observationResponseElement)) {
            return $output;
        }

        $messageElements = $observationResponseElement->getElementsByTagName('message');

        foreach ($messageElements as $messageElement) {
            $message = MessageFactory::createFromDOMElement($messageElement);
            $isVendorExtensionMessage = $this->isVendorExtensionMessage($message);
            $isError = $message->isError();

            if ($configuration->getReportVendorExtensionIssuesAsWarnings() && $isError && $isVendorExtensionMessage) {
                $message = MessageFactory::createWarningFromError($message);
            }

            if ($message->isWarning() && $configuration->getIgnoreWarnings()) {
                continue;
            }

            if ($this->hasRefDomainToIgnore($message, $configuration->getRefDomainsToIgnore())) {
                continue;
            }

            if ($configuration->getIgnoreVendorExtensionIssues() && $isVendorExtensionMessage) {
                continue;
            }

            if ($configuration->getIgnoreFalseImageDataUrlMessages() && $this->isFalseImageDataUrlMessage($message)) {
                continue;
            }

            $messageList->addMessage($message);
        }

        return $output;
    }

    private function extractXmlContentFromBody(string $body): ?string
    {
        $bodyLines = explode("\n", $body);

        $xmlContentStartLineNumber = $this->getXmlContentStartLineNumber($bodyLines);
        if (null === $xmlContentStartLineNumber) {
            return null;
        }

        return implode("\n", array_slice($bodyLines, $this->getXmlContentStartLineNumber($bodyLines)));
    }

    private function getXmlContentStartLineNumber(array $bodyLines): ?int
    {
        $xmlPremableStart = '<?xml';

        foreach ($bodyLines as $lineIndex => $line) {
            if (substr($line, 0, strlen($xmlPremableStart)) == $xmlPremableStart) {
                return $lineIndex;
            }
        }

        return null;
    }

    private function isFalseImageDataUrlMessage(AbstractMessage $message): bool
    {
        $propertyNames = [
            'background-image',
            'background',
            'list-style-image'
        ];

        $propertyNamesPatternPart = implode('|', $propertyNames);

        $valueErrorLinePattern = sprintf(
            '/Value Error\s*:\s*(%s)\s\(null.*\.html#propdef-(%s)\)/',
            $propertyNamesPatternPart,
            $propertyNamesPatternPart
        );

        $dataUrlLinePattern = '/^url\(data:image\/.*is an incorrect URL$/';

        $messageContent = $message->getTitle();

        if (preg_match($valueErrorLinePattern, $messageContent)) {
            $messageLines = explode("\n", $messageContent);
            $firstMessageLine = trim($messageLines[1]);

            if (preg_match($dataUrlLinePattern, $firstMessageLine)) {
                return true;
            }
        }

        return false;
    }

    private function isPassedNoMessagesOutput(\DomElement $outputContainer): bool
    {
        $statusElements = $outputContainer->getElementsByTagName('status');
        if (0 === $statusElements->length) {
            return false;
        }

        $statusElement = $statusElements->item(0);

        return 'passed' === $statusElement->getAttribute('value');
    }

    private function hasRefDomainToIgnore(AbstractMessage $message, array $refDomainsToIgnore): bool
    {
        if (!$message->isError()) {
            return false;
        }

        /* @var ErrorMessage $message */
        if ('' === $message->getRef()) {
            return false;
        }

        $messageRefUrl = new Url($message->getRef());
        foreach ($refDomainsToIgnore as $refDomainToIgnore) {
            if ($messageRefUrl->hasHost() && $messageRefUrl->getHost()->isEquivalentTo(new Host($refDomainToIgnore))) {
                return true;
            }
        }

        return false;
    }

    private function isIncorrectUsageOutput(string $header): bool
    {
        return preg_match('/^Usage/', $header) > 0;
    }

    private function isVendorExtensionMessage(AbstractMessage $message): bool
    {
        $patterns = [
            '/is an unknown vendor extension/', #
            '/^Property \-[a-z\-]+ doesn\&#39;t exist/', #
            '/^Unknown pseudo\-element or pseudo\-class [:]{1,2}\-[a-z\-]+/', #
            '/-webkit\-focus\-ring\-color is not a outline\-color value/',
            '/Sorry, the at\-rule @\-[a-z\-]+ is not implemented./'
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $message->getTitle()) > 0) {
                return true;
            }
        }

        return false;
    }

    private function createObservationResponseDateTime(\DOMElement $observationResponseElement)
    {
        try {
            return new \DateTime($observationResponseElement->getAttribute('date'));
        } catch (\Exception $e) {
            /** @noinspection PhpUnhandledExceptionInspection */
            return new \DateTime();
        }
    }
}
