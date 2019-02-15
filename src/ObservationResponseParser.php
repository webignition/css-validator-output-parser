<?php

namespace webignition\CssValidatorOutput\Parser;

use webignition\CssValidatorOutput\Model\AbstractMessage;
use webignition\CssValidatorOutput\Model\ErrorMessage;
use webignition\CssValidatorOutput\Model\MessageFactory;
use webignition\CssValidatorOutput\Model\MessageList;
use webignition\CssValidatorOutput\Model\ObservationResponse;

class ObservationResponseParser
{
    public function parse(\DOMElement $observationResponseElement, int $flags = Flags::NONE): ObservationResponse
    {
        $sourceUrl = $observationResponseElement->getAttribute('ref');
        $dateTime = $this->createObservationResponseDateTime($observationResponseElement);

        $messageList = new MessageList();
        $observationResponse = new ObservationResponse($sourceUrl, $dateTime, $messageList);

        if ($this->isPassedNoMessagesOutput($observationResponseElement)) {
            return $observationResponse;
        }

        $messageElements = $observationResponseElement->getElementsByTagName('message');

        foreach ($messageElements as $messageElement) {
            $message = MessageFactory::createFromDOMElement($messageElement);

            if (null === $message) {
                continue;
            }

            $isVendorExtensionMessage = $this->isVendorExtensionMessage($message);
            $reportVExtIssuesAsWarnings = $flags & Flags::REPORT_VENDOR_EXTENSION_ISSUES_AS_WARNINGS;
            $ignoreWarnings = $flags & Flags::IGNORE_WARNINGS;
            $ignoreVendorExtensionIssues = $flags & Flags::IGNORE_VENDOR_EXTENSION_ISSUES;
            $ignoreFalseImageDataUrlMessages = $flags & Flags::IGNORE_FALSE_IMAGE_DATA_URL_MESSAGES;

            if ($reportVExtIssuesAsWarnings && $isVendorExtensionMessage && $message instanceof ErrorMessage) {
                $message = MessageFactory::createWarningFromError($message);
            }

            if ($ignoreWarnings && $message->isWarning()) {
                continue;
            }

            if ($ignoreVendorExtensionIssues && $isVendorExtensionMessage) {
                continue;
            }

            if ($ignoreFalseImageDataUrlMessages && $this->isFalseImageDataUrlMessage($message)) {
                continue;
            }

            $messageList->addMessage($message);
        }

        return $observationResponse;
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

    private function isPassedNoMessagesOutput(\DOMElement $observationResponseElement): bool
    {
        $statusElements = $observationResponseElement->getElementsByTagName('status');
        if (0 === $statusElements->length) {
            return false;
        }

        $statusElement = $statusElements->item(0);

        return $statusElement instanceof \DOMElement && 'passed' === $statusElement->getAttribute('value');
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
