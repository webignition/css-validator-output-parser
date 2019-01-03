<?php

namespace webignition\CssValidatorOutput\Parser;

use webignition\CssValidatorOutput\Model\IncorrectUsageOutput;
use webignition\CssValidatorOutput\Model\OutputInterface;
use webignition\CssValidatorOutput\Model\ValidationOutput;

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

        $observationResponseParser = new ObservationResponseParser();
        $observationResponse = $observationResponseParser->parse($observationResponseElement, $configuration);

        return new ValidationOutput($options, $observationResponse);
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

    private function isIncorrectUsageOutput(string $header): bool
    {
        return preg_match('/^Usage/', $header) > 0;
    }
}
