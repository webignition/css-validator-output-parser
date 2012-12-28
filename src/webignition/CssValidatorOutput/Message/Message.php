<?php

namespace webignition\CssValidatorOutput\Message;

abstract class Message {
    
    const TYPE_ERROR = 0;
    const TYPE_WARNING = 1;
    const TYPE_INFO = 2;
    
    /**
     *
     * @var string
     */
    private $body = '';
    
    /**
     *
     * @var string
     */
    private $context = '';
    
    /**
     *
     * @var int
     */
    private $lineNumber = 0;
    
    /**
     *
     * @var int
     */
    private $type = self::TYPE_ERROR;
    
    
    /**
     * 
     * @param string $body
     * @return \webignition\CssValidatorOutput\Message
     */
    public function setBody($body) {
        $this->body = $body;
        return $this;
    }
    
    /**
     * 
     * @return string
     */
    public function getBody() {
        return $this->body;
    }
    
    
    /**
     * 
     * @param string $context
     * @return \webignition\CssValidatorOutput\Message
     */
    public function setContext($context) {
        $this->context = $context;
        return $this;
    }
    
    
    /**
     * 
     * @return string
     */
    public function getContext() {
        return $this->context;
    }
    
    
    /**
     * 
     * @param int $lineNumber
     * @return \webignition\CssValidatorOutput\Message
     */
    public function setLineNumber($lineNumber) {
        $this->lineNumber = (int)$lineNumber;
        return $this;
    }
    
    
    /**
     * 
     * @return int
     */
    public function getLineNumber() {
        return $this->lineNumber;
    }
    
    
    /**
     * 
     * @param int $type
     * @return \webignition\CssValidatorOutput\Message
     */
    public function setType($type) {
        $this->type = $type;
        return $this;
    }
    
    /**
     * 
     * @return int
     */
    public function getType() {
        return $this->type;
    }
    
    
    /**
     * 
     * @return boolean
     */
    public function isError() {
        return $this->getType() == self::TYPE_ERROR;
    }
    
    
    /**
     * 
     * @return boolean
     */
    public function isWarning() {
        return $this->getType() == self::TYPE_WARNING;
    }
    
    
    /**
     * 
     * @param \webignition\CssValidatorOutput\Message $message
     * @return boolean
     */
    public function equals(Message $message) {
        if ($this->getBody() != $message->getBody()) {
            return false;
        }
        
        if ($this->getContext() != $message->getContext()) {
            return false;
        }
        
        if ($this->getLineNumber() != $message->getLineNumber()) {
            return false;
        }
        
        if ($this->getRef() != $message->getRef()) {
            return false;
        }
        
        if ($this->getType() != $message->getType()) {
            return false;
        }
        
        return true;        
    }
    
}
 