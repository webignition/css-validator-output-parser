<?php

namespace webignition\CssValidatorOutput\Parser;

use webignition\CssValidatorOutput\CssValidatorOutput;
use webignition\CssValidatorOutput\Message\AbstractMessage;
use webignition\CssValidatorOutput\Message\Error;
use webignition\CssValidatorOutput\Options\Parser as OptionsParser;
use webignition\CssValidatorOutput\Message\Factory as MessageFactory;
use webignition\CssValidatorOutput\Parser\Sanitizer;
use webignition\Url\Host\Host;
use webignition\Url\Url;

use webignition\CssValidatorOutput\ExceptionOutput\Parser as ExceptionOutputParser;

class Parser
{
    /**
     * @param string $validatorOutput
     * @param Configuration $configuration
     *
     * @return CssValidatorOutput
     *
     * @throws InvalidValidatorOutputException
     */
    public function parse(string $validatorOutput, Configuration $configuration): CssValidatorOutput
    {
        $sanitizer = new Sanitizer();
        $validatorOutput = trim($sanitizer->getSanitizedOutput($validatorOutput));

        $headerBodyParts = explode("\n", $validatorOutput, 2);
        $header = trim($headerBodyParts[0]);
        $body = trim($headerBodyParts[1]);

        $output = new CssValidatorOutput();

        if (ExceptionOutputParser::is($body)) {
            $exceptionOutputParser = new ExceptionOutputParser();
            $exceptionOutputParser->setRawOutput($body);

            $output->setException($exceptionOutputParser->getOutput());

            return $output;
        }

        if ($this->isIncorrectUsageOutput($header)) {
            $output->setIsIncorrectUsageOutput(true);

            return $output;
        }

        $bodyXmlContent = $this->extractXmlContentFromBody($body);
        if (null === $bodyXmlContent) {
            throw new InvalidValidatorOutputException($validatorOutput);
        }

        $optionsParser = new OptionsParser();
        $output->setOptions($optionsParser->parse($header));

        $bodyDom = new \DOMDocument();
        $bodyDom->loadXML($bodyXmlContent);

        $container = $bodyDom->getElementsByTagName('observationresponse')->item(0);

        if ($this->isPassedNoMessagesOutput($container)) {
            return $output;
        }

        $output->setSourceUrl($container->getAttribute('ref'));
        $output->setDateTime(new \DateTime($container->getAttribute('date')));

        $messageElements = $container->getElementsByTagName('message');

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

            $output->addMessage($message);
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

        $messageContent = $message->getMessage();

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

        /* @var Error $message */
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
            if (preg_match($pattern, $message->getMessage()) > 0) {
                return true;
            }
        }

        return false;
    }
}
