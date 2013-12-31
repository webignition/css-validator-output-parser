<?php

namespace webignition\CssValidatorOutput\ExceptionOutput;

use webignition\CssValidatorOutput\ExceptionOutput\Type\Type;

class ExceptionOutput {

    /**
     *
     * @var Type
     */
    private $type = null;    
    
    
    /**
     * 
     * @param Type $type
     */
    public function setType(Type $type) {
        $this->type = $type;
    }
    
    
    /**
     * 
     * @param string $name
     * @param array $arguments Not Used
     * @return boolean
     */
    public function __call($name, $arguments) {        
        return $this->type->get() == str_replace('is', '', strtolower($name));
    }
    
}