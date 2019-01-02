<?php

namespace webignition\CssValidatorOutput;

use webignition\CssValidatorOutput\Message\AbstractMessage;
use webignition\CssValidatorOutput\Message\Error;
use webignition\CssValidatorOutput\Message\Warning;
use webignition\CssValidatorOutput\Options\Options as CssValidatorOutputOptions;
use webignition\CssValidatorOutput\ExceptionOutput\ExceptionOutput;

class CssValidatorOutput
{
    /**
     * @var AbstractMessage[]
     */
    private $messages = array();

    /**
     * @var int
     */
    private $errorCount = 0;

    /**
     * @var int
     */
    private $warningCount = 0;

    /**
     * @var CssValidatorOutputOptions
     */
    private $options;

    /**
     * @var string
     */
    private $sourceUrl = '';

    /**
     * @var \DateTime
     */
    private $datetime = null;

    /**
     * @var ExceptionOutput
     */
    private $exceptionOutput = null;

    /**
     * @var bool
     */
    private $isIncorrectUsageOutput = false;

    public function setException(ExceptionOutput $exceptionOutput)
    {
        $this->exceptionOutput = $exceptionOutput;
    }

    public function getException(): ?ExceptionOutput
    {
        return $this->exceptionOutput;
    }

    public function hasException(): bool
    {
        return $this->getException() instanceof ExceptionOutput;
    }

    public function setIsIncorrectUsageOutput(bool $isIncorrectUsageOutput)
    {
        $this->isIncorrectUsageOutput = $isIncorrectUsageOutput;
    }

    public function getIsIncorrectUsageOutput(): bool
    {
        return $this->isIncorrectUsageOutput;
    }

    public function addMessage(AbstractMessage $message)
    {
        $this->messages[] = $message;

        if ($message->isError()) {
            $this->errorCount++;
        }

        if ($message->isWarning()) {
            $this->warningCount++;
        }
    }

    public function setOptions(CssValidatorOutputOptions $options)
    {
        $this->options = $options;
    }

    public function getOptions(): ?CssValidatorOutputOptions
    {
        if ($this->getIsIncorrectUsageOutput()) {
            return null;
        }

        return $this->options;
    }

    public function setDateTime(\DateTime $datetime)
    {
        $this->datetime = $datetime;
    }

    public function getDateTime(): ?\DateTime
    {
        if ($this->getIsIncorrectUsageOutput() || $this->hasException()) {
            return null;
        }

        return $this->datetime;
    }

    public function setSourceUrl(string $sourceUrl)
    {
        $this->sourceUrl = $sourceUrl;
    }

    public function getSourceUrl(): string
    {
        return $this->sourceUrl;
    }

    public function getErrorCount(): int
    {
        return $this->errorCount;
    }

    public function getWarningCount(): int
    {
        return $this->warningCount;
    }

    public function getMessageCount(): int
    {
        return $this->getErrorCount() + $this->getWarningCount();
    }

    /**
     * @return AbstractMessage[]
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @return Error[]
     */
    public function getErrors(): array
    {
        return $this->getMessagesOfType(AbstractMessage::TYPE_ERROR);
    }

    /**
     *
     * @param string $url
     *
     * @return Error[]
     */
    public function getErrorsByUrl(string $url): array
    {
        $errors = array();

        foreach ($this->getMessages() as $message) {
            if ($message->isError() && $message->getRef() === $url) {
                $errors[] = $message;
            }
        }

        return $errors;
    }

    /**
     * @return Warning[]
     */
    public function getWarnings(): array
    {
        return $this->getMessagesOfType(AbstractMessage::TYPE_WARNING);
    }

    /**
     * @param string $selectedMessageType
     *
     * @return Error[]|Warning[]
     */
    private function getMessagesOfType(string $selectedMessageType): array
    {
        $messages = array();

        foreach ($this->messages as $message) {
            if ($message->getType() == $selectedMessageType) {
                $messages[] = $message;
            }
        }

        return $messages;
    }
}
