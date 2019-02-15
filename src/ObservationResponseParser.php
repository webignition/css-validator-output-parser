<?php

namespace webignition\CssValidatorOutput\Parser;

use webignition\CssValidatorOutput\Model\AbstractMessage;
use webignition\CssValidatorOutput\Model\ErrorMessage;
use webignition\CssValidatorOutput\Model\MessageFactory;
use webignition\CssValidatorOutput\Model\MessageList;
use webignition\CssValidatorOutput\Model\ObservationResponse;
use webignition\Url\Host\Host;
use webignition\Url\Url;

class ObservationResponseParser
{
    public function parse(\DOMElement $observationResponseElement, Configuration $configuration): ObservationResponse
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
            $reportVExtIssuesAsWarnings = $configuration->getReportVendorExtensionIssuesAsWarnings();

            if ($reportVExtIssuesAsWarnings && $isVendorExtensionMessage && $message instanceof ErrorMessage) {
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

    private function hasRefDomainToIgnore(AbstractMessage $message, array $refDomainsToIgnore): bool
    {
        if (!$message instanceof ErrorMessage) {
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
