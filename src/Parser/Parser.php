<?php

namespace webignition\CssValidatorOutput\Parser;

use webignition\CssValidatorOutput\CssValidatorOutput;
use webignition\CssValidatorOutput\Message\AbstractMessage;
use webignition\CssValidatorOutput\Message\Error;
use webignition\CssValidatorOutput\Options\Parser as OptionsParser;
use webignition\CssValidatorOutput\Message\Factory as MessageFactory;
use webignition\Url\Host\Host;
use webignition\Url\Url;

use webignition\CssValidatorOutput\ExceptionOutput\Parser as ExceptionOutputParser;

class Parser
{
    /**
     * @var Configuration
     */
    private $configuration = null;

    /**
     * @var CssValidatorOutput
     */
    private $output;

    /**
     * @var string
     */
    private $rawHeader = null;

    /**
     * @var string
     */
    private $rawBody = null;

    /**
     * @param Configuration $configuration
     */
    public function setConfiguration(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @return Configuration
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @return CssValidatorOutput
     *
     * @throws InvalidValidatorOutputException
     */
    public function getOutput()
    {
        if (is_null($this->output)) {
            $this->output = new CssValidatorOutput();
            $this->parse();
        }

        return $this->output;
    }

    /**
     * @throws InvalidValidatorOutputException
     */
    private function parse()
    {
        $configuration = $this->configuration;

        $headerBodyParts = explode("\n", $configuration->getRawOutput(), 2);
        $header = trim($headerBodyParts[0]);
        $body = trim($headerBodyParts[1]);

        if (ExceptionOutputParser::is($body)) {
            $exceptionOutputParser = new ExceptionOutputParser();
            $exceptionOutputParser->setRawOutput($body);

            $this->output->setException($exceptionOutputParser->getOutput());

            return;
        }

        if ($this->isIncorrectUsageOutput($header)) {
            $this->output->setIsIncorrectUsageOutput(true);

            return;
        }

        $this->rawHeader = $header;
        $this->rawBody = $body;

        $bodyXmlContent = $this->extractXmlContentFromBody($body);
        if (null === $bodyXmlContent) {
            throw new InvalidValidatorOutputException($configuration->getRawOutput());
        }

        $optionsParser = new OptionsParser();
        $optionsParser->setOptionsOutput($header);

        $this->output->setOptions($optionsParser->getOptions());

        $bodyDom = new \DOMDocument();
        $bodyDom->loadXML($bodyXmlContent);

        $container = $bodyDom->getElementsByTagName('observationresponse')->item(0);

        if ($this->isPassedNoMessagesOutput($container)) {
            return;
        }

        $this->output->setSourceUrl($container->getAttribute('ref'));
        $this->output->setDateTime(new \DateTime($container->getAttribute('date')));

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

            if ($this->hasRefDomainToIgnore($message)) {
                continue;
            }

            if ($configuration->getIgnoreVendorExtensionIssues() && $isVendorExtensionMessage) {
                continue;
            }

            if ($configuration->getIgnoreFalseImageDataUrlMessages() && $this->isFalseImageDataUrlMessage($message)) {
                continue;
            }

            $this->output->addMessage($message);
        }
    }

    /**
     * @param string $body
     *
     * @return string
     */
    private function extractXmlContentFromBody($body)
    {
        $bodyLines = explode("\n", $body);

        $xmlContentStartLineNumber = $this->getXmlContentStartLineNumber($bodyLines);
        if (null === $xmlContentStartLineNumber) {
            return null;
        }

        return implode("\n", array_slice($bodyLines, $this->getXmlContentStartLineNumber($bodyLines)));
    }

    /**
     * @param string[] $bodyLines
     *
     * @return int
     */
    private function getXmlContentStartLineNumber($bodyLines)
    {
        $xmlPremableStart = '<?xml';

        foreach ($bodyLines as $lineIndex => $line) {
            if (substr($line, 0, strlen($xmlPremableStart)) == $xmlPremableStart) {
                return $lineIndex;
            }
        }

        return null;
    }

    /**
     * @param AbstractMessage $message
     *
     * @return bool
     */
    private function isFalseImageDataUrlMessage(AbstractMessage $message)
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


    /**
     * @param \DomElement $outputContainer
     *
     * @return bool
     */
    private function isPassedNoMessagesOutput(\DomElement $outputContainer)
    {
        $statusElements = $outputContainer->getElementsByTagName('status');
        if (0 === $statusElements->length) {
            return false;
        }

        $statusElement = $statusElements->item(0);

        return 'passed' === $statusElement->getAttribute('value');
    }

    /**
     * @param AbstractMessage $message
     *
     * @return bool
     */
    private function hasRefDomainToIgnore(AbstractMessage $message)
    {
        if (!$message->isError()) {
            return false;
        }

        /* @var Error $message */
        if ('' === $message->getRef()) {
            return false;
        }

        $messageRefUrl = new Url($message->getRef());
        foreach ($this->configuration->getRefDomainsToIgnore() as $refDomainToIgnore) {
            if ($messageRefUrl->hasHost() && $messageRefUrl->getHost()->isEquivalentTo(new Host($refDomainToIgnore))) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $header
     *
     * @return bool
     */
    private function isIncorrectUsageOutput($header)
    {
        return preg_match('/^Usage/', $header) > 0;
    }

    /**
     * @param AbstractMessage $message
     *
     * @return bool
     */
    private function isVendorExtensionMessage(AbstractMessage $message)
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
