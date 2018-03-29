<?php

namespace webignition\CssValidatorOutput\ExceptionOutput\Type;

class Type {
    
    /**
     *
     * @var int
     */
    private $value = null;    
    
    public function __construct($value) {
        $this->value = $value;
    }
    
    
    /**
     * 
     * @return int
     */
    public function get() {
        return $this->value;
    }
    
}