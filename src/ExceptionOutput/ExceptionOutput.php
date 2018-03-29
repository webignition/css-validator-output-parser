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
    
    
    /**
     * 
     * @return \webignition\CssValidatorOutput\ExceptionOutput\Type\Type
     */
    public function getType() {
        return $this->type;
    }
    
    
    /**
     * 
     * @return boolean
     */
    public function isHttpError() {
        return substr($this->type->get(), 0, strlen('http')) === 'http';
    }
    
    
    /**
     * 
     * @return boolean
     */
    public function isHttpClientError() {
        return substr($this->type->get(), 0, strlen('http4')) === 'http4';
    }
    
    
    /**
     * 
     * @return boolean
     */
    public function isHttpServerError() {
        return substr($this->type->get(), 0, strlen('http5')) === 'http5';
    }
    
    
    /**
     * 
     * @return int
     */
    public function getHttpStatusCode() {
        if (!$this->isHttpError()) {
            return null;
        }
        
        return (int)str_replace('http', '', $this->type->get());
    }
    
    
    /**
     * 
     * @return boolean
     */
    public function isCurlError() {
        return substr($this->type->get(), 0, strlen('curl')) === 'curl';
    }
    
    
    /**
     * 
     * @return int
     */
    public function getCurlCode() {
        if (!$this->isCurlError()) {
            return null;
        }
        
        return (int)str_replace('curl', '', $this->type->get());        
    }
    
}