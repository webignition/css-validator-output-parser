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
     * @JMS\Serializer\Annotation\Accessor(getter="getSerializedType")
     */
    private $type = self::TYPE_ERROR;
    
    
    /**
     *
     * @var array
     */
    private $serializedTypes = array(
        'error',
        'warning'
    );    
    
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
        $this->lineNumber = filter_var($lineNumber, FILTER_VALIDATE_INT, array('options' => array(
            'min_range' => 0,
            'default' => 0
        )));

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
    protected function setType($type) {
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
     * @return string
     */
    public function getSerializedType() {
        return $this->serializedTypes[$this->getType()];
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
     * @return string
     */
    public function getHash() {
        return md5($this->getBody().$this->getContext().$this->getLineNumber().$this->getType());
    }

    }

