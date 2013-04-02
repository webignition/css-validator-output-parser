<?php

namespace webignition\CssValidatorOutput;

use webignition\CssValidatorOutput\Options\Options as CssValidatorOutputOptions;
use webignition\CssValidatorOutput\Message\Message;

class CssValidatorOutput {
    
    /**
     *
     * @var array
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
     * @var boolean
     */
    private $isIncorrectUsageOutput = false;
    
    
    /**
     *
     * @var boolean
     */
    private $isUnknownMimeTypeError = false;
    
    
    /**
     *
     * @var boolean
     */
    private $isUnknownExceptionError = false;
    
    
    /**
     *
     * @var boolean
     */
    private $isInternalServerError = false;
    
    /**
     *
     * @var boolean
     */
    private $isFileNotFoundError = false;   
    
    
    /**
     *
     * @var boolean
     */
    private $isUknownHostError = false;
    
    
    public function __construct() {
        $this->options = new CssValidatorOutputOptions();
    }
    
    
    /**
     * 
     * @return boolean
     */
    public function hasExceptionError() {
        if ($this->getIsInternalServerErrorOutput()) {
            return true;
        }
        
        if ($this->getIsUnknownMimeTypeError()) {
            return true;
        }
        
        if ($this->getIsUnknownExceptionError()) {
            return true;
        }
        
        if ($this->getIsFileNotFoundErrorOutput()) {
            return true;
        }
        
        if ($this->getIsUnknownHostErrorOutput()) {
            return true;
        }
        
        return false;
    }
    
    
    /**
     * 
     * @param boolean $isInternalServerErrorOutput
     * @return \webignition\CssValidatorOutput\CssValidatorOutput
     */
    public function setIsInternalServerErrorOutput($isInternalServerErrorOutput) {
        $this->isInternalServerError = $isInternalServerErrorOutput;
        return $this;
    }
    
    
    /**
     * 
     * @return boolean
     */
    public function getIsInternalServerErrorOutput() {
        return $this->isInternalServerError;
    } 
    
    
    /**
     * 
     * @param boolean $isUnknownHostErrorOutput
     * @return \webignition\CssValidatorOutput\CssValidatorOutput
     */
    public function setIsUnknownHostErrorOutput($isUnknownHostErrorOutput) {
        $this->isUknownHostError = $isUnknownHostErrorOutput;
        return $this;
    }
    
    
    /**
     * 
     * @return boolean
     */
    public function getIsUnknownHostErrorOutput() {
        return $this->isUknownHostError;
    }     
    
    
    /**
     * 
     * @param boolean $isFileNotFoundErrorOutput
     * @return \webignition\CssValidatorOutput\CssValidatorOutput
     */
    public function setIsFileNotFoundErrorOutput($isFileNotFoundErrorOutput) {
        $this->isFileNotFoundError = $isFileNotFoundErrorOutput;
        return $this;
    }
    
    
    /**
     * 
     * @return boolean
     */
    public function getIsFileNotFoundErrorOutput() {
        return $this->isFileNotFoundError;
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
     * @param boolean $isUnknownMimeTypeError
     * @return \webignition\CssValidatorOutput\CssValidatorOutput
     */
    public function setIsUnknownMimeTypeError($isUnknownMimeTypeError) {
        $this->isUnknownMimeTypeError = $isUnknownMimeTypeError;
        return $this;
    }
    
    
    /**
     * 
     * @return boolean
     */
    public function getIsUnknownMimeTypeError() {
        return $this->isUnknownMimeTypeError;
    }
    
    
    /**
     * 
     * @param boolean $isUnknownExceptionError
     * @return \webignition\CssValidatorOutput\CssValidatorOutput
     */
    public function setIsUnknownExceptionError($isUnknownExceptionError) {
        $this->isUnknownExceptionError = $isUnknownExceptionError;
        return $this;
    }
    
    
    /**
     * 
     * @return boolean
     */
    public function getIsUnknownExceptionError() {
        return $this->isUnknownExceptionError;
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
        if ($this->getIsIncorrectUsageOutput() || $this->getIsUnknownMimeTypeError()) {
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