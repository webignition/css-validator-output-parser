<?php

namespace webignition\CssValidatorOutput\Message;

class Error extends Message {

    /**
     *
     * @var string
     */
    private $ref = '';    
    
    
    public function __construct() {
        $this->setType(self::TYPE_ERROR);
    }
    
    
    /**
     * 
     * @param string $ref
     * @return \webignition\CssValidatorOutput\Message\Error
     */
    public function setRef($ref) {
        $this->ref = $ref;
        return $this;
    }
    
    
    /**
     * 
     * @return string
     */
    public function getRef() {
        return $this->ref;
    }
    
}
 