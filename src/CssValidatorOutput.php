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

    /**
     * @param ExceptionOutput $exceptionOutput
     */
    public function setException(ExceptionOutput $exceptionOutput)
    {
        $this->exceptionOutput = $exceptionOutput;
    }

    /**
     * @return ExceptionOutput
     */
    public function getException()
    {
        return $this->exceptionOutput;
    }

    /**
     * @return bool
     */
    public function hasException()
    {
        return $this->getException() instanceof ExceptionOutput;
    }

    /**
     * @param bool $isIncorrectUsageOutput
     *
     * @return CssValidatorOutput
     */
    public function setIsIncorrectUsageOutput($isIncorrectUsageOutput)
    {
        $this->isIncorrectUsageOutput = $isIncorrectUsageOutput;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsIncorrectUsageOutput()
    {
        return $this->isIncorrectUsageOutput;
    }

    /**
     * @param AbstractMessage $message
     */
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

    /**
     * @param CssValidatorOutputOptions $options
     *
     * @return CssValidatorOutput
     */
    public function setOptions(CssValidatorOutputOptions $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @return CssValidatorOutputOptions
     */
    public function getOptions()
    {
        if ($this->getIsIncorrectUsageOutput()) {
            return null;
        }

        return $this->options;
    }

    /**
     * @param \DateTime $datetime
     *
     * @return CssValidatorOutput
     */
    public function setDateTime(\DateTime $datetime)
    {
        $this->datetime = $datetime;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateTime()
    {
        if ($this->getIsIncorrectUsageOutput() || $this->hasException()) {
            return null;
        }

        return $this->datetime;
    }

    /**
     * @param string $sourceUrl
     *
     * @return CssValidatorOutput
     */
    public function setSourceUrl($sourceUrl)
    {
        $this->sourceUrl = $sourceUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getSourceUrl()
    {
        return $this->sourceUrl;
    }

    /**
     * @return int
     */
    public function getErrorCount()
    {
        return $this->errorCount;
    }

    /**
     * @return int
     */
    public function getWarningCount()
    {
        return $this->warningCount;
    }

    /**
     * @return int
     */
    public function getMessageCount()
    {
        return $this->getErrorCount() + $this->getWarningCount();
    }

    /**
     * @return AbstractMessage[]
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @return Error[]
     */
    public function getErrors()
    {
        return $this->getMessagesOfType(AbstractMessage::TYPE_ERROR);
    }

    /**
     *
     * @param string $url
     *
     * @return Error[]
     */
    public function getErrorsByUrl($url)
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
    public function getWarnings()
    {
        return $this->getMessagesOfType(AbstractMessage::TYPE_WARNING);
    }

    /**
     * @param int $selectedMessageType
     *
     * @return Error[]|Warning[]
     */
    private function getMessagesOfType($selectedMessageType)
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
