<?php

namespace webignition\CssValidatorOutput;

use webignition\CssValidatorOutput\Options\Options as CssValidatorOutputOptions;
use webignition\CssValidatorOutput\Message\Message;
use webignition\CssValidatorOutput\ExceptionOutput\ExceptionOutput;

class CssValidatorOutput {
    
    /**
     *
     * @var \webignition\CssValidatorOutput\Message\Message[]
     */
    private $messages = array();
    
    /**
     *
     * @var int
     */
    private $errorCount = 0;
    
    /**
     *
     * @var int
     */
    private $warningCount = 0;
    
    
    /**
     *
     * @var CssValidatorOutputOptions
     */
    private $options;
    
    
    /**
     *
     * @var string
     */
    private $sourceUrl = '';
    
    
    /**
     *
     * @var \DateTime
     */
    private $datetime = null;
    
    
    /**
     *
     * @var \webignition\CssValidatorOutput\ExceptionOutput\ExceptionOutput
     */
    private $exceptionOutput = null;
    
    
    /**
     *
     * @var boolean
     */
    private $isIncorrectUsageOutput = false;
    
    
    public function __construct() {
        $this->options = new CssValidatorOutputOptions();
    }
    
    
    /**
     * 
     * @param \webignition\CssValidatorOutput\ExceptionOutput\ExceptionOutput $exceptionOutput
     */
    public function setException(ExceptionOutput $exceptionOutput) {
        $this->exceptionOutput = $exceptionOutput;
    }
    
    
    /**
     * 
     * @return \webignition\CssValidatorOutput\ExceptionOutput\ExceptionOutput
     */
    public function getException() {
        return $this->exceptionOutput;
    }
    
    
    /**
     * 
     * @return boolean
     */
    public function hasException() {
        return $this->getException() instanceof \webignition\CssValidatorOutput\ExceptionOutput\ExceptionOutput;
    }
    
    /**
     * 
     * @param boolean $isIncorrectUsageOutput
     * @return \webignition\CssValidatorOutput\CssValidatorOutput
     */
    public function setIsIncorrectUsageOutput($isIncorrectUsageOutput) {
        $this->isIncorrectUsageOutput = $isIncorrectUsageOutput;
        return $this;
    }
    
    
    /**
     * 
     * @return boolean
     */
    public function getIsIncorrectUsageOutput() {
        return $this->isIncorrectUsageOutput;        
    }
    
    
    /**
     * 
     * @param \webignition\CssValidatorOutput\Message\Message $message
     */
    public function addMessage(Message $message) {
        $this->messages[] = $message;

        if ($message->isError()) {
            $this->errorCount++;
        }

        if ($message->isWarning()) {
            $this->warningCount++;
        }
    }
    
    
    /**
     * 
     * @param \webignition\CssValidatorOutput\Options\Options $options
     * @return \webignition\CssValidatorOutput\CssValidatorOutput
     */
    public function setOptions(CssValidatorOutputOptions $options) {
        $this->options = $options;
        return $this;
    }
    
    
    /**
     * 
     * @return \webignition\CssValidatorOutput\Options\Options
     */
    public function getOptions() {
        if ($this->getIsIncorrectUsageOutput()) {
            return null;
        }
        
        return $this->options;
    }
    
    
    /**
     * 
     * @param \DateTime $datetime
     * @return \webignition\CssValidatorOutput\CssValidatorOutput
     */
    public function setDateTime(\DateTime $datetime) {
        $this->datetime = $datetime;
        return $this;
    }
    
    /**
     * 
     * @return \DateTime
     */
    public function getDateTime() {
        if ($this->getIsIncorrectUsageOutput() || $this->hasException()) {
            return null;
        }
        
        return $this->datetime;
    }
    
    
    /**
     * 
     * @param string $sourceUrl
     * @return \webignition\CssValidatorOutput\CssValidatorOutput
     */
    public function setSourceUrl($sourceUrl) {
        $this->sourceUrl = $sourceUrl;
        return $this;
    }
    
    
    /**
     * 
     * @return string
     */
    public function getSourceUrl() {
        return $this->sourceUrl;
    }
    
    
    /**
     * 
     * @return int
     */
    public function getErrorCount() {
        return $this->errorCount;
    }
    

    /**
     * 
     * @return int
     */    
    public function getWarningCount() {
        return $this->warningCount;
    }
    
    
    /**
     * 
     * @return int
     */    
    public function getMessageCount() {
        return $this->getErrorCount() + $this->getWarningCount();
    }
    
    
    /**
     * 
     * @return array
     */
    public function getMessages() {
        return $this->messages;
    }

    /**
     * 
     * @return array
     */
    public function getErrors() {
        return $this->getMessagesOfType(Message::TYPE_ERROR);
    }
    
    
    /**
     * 
     * @param type $url
     * @return \webignition\CssValidatorOutput\Message\Error[]
     */
    public function getErrorsByUrl($url) {
        $errors = array();
        
        foreach ($this->getMessages() as $message) {
            if ($message->isError() && $message->getRef() === $url) {
                $errors[] = $message;
            }
        }
        
        return $errors;
    }
    
    
    /**
     * 
     * @return array
     */
    public function getWarnings() {
        return $this->getMessagesOfType(Message::TYPE_WARNING);
    }
    
    /**
     * 
     * @param int $selectedMessageType
     * @return array
     */
    private function getMessagesOfType($selectedMessageType) {
        $messages = array();
        
        foreach ($this->messages as $message) {
            if ($message->getType() == $selectedMessageType) {
                $messages[] = $message;
            }
        }
        
        return $messages;
    }   
    
}