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
     * Collection of message hashes, used to determine if output already
     * contains a message
     * 
     * @var array
     */
    private $messageIndex = array();
    
    
    
    public function __construct() {
        $this->options = new CssValidatorOutputOptions();
        $this->datetime = new \DateTime();
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
        if (!$this->contains($message)) {
            $this->messages[$message->getHash()] = $message;
            $this->messageIndex[$message->getHash()] = $message->getType();
            
            if ($message->isError()) {
                $this->errorCount++;
            }
            
            if ($message->isWarning()) {
                $this->warningCount++;
            }
        }        
    }
    
    
    /**
     * 
     * @param \webignition\CssValidatorOutput\Message\Message $message
     * @return boolean
     */
    public function contains(Message $message) {        
        return array_key_exists($message->getHash(), $this->messageIndex);
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
        if ($this->getIsIncorrectUsageOutput()) {
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
        foreach ($this->messageIndex as $messageHash => $messageType) {
            if ($messageType == $selectedMessageType) {
                $messages[] = $this->messages[$messageHash];
            }
        }
        
        return $messages;
    }    
    
    
    
}