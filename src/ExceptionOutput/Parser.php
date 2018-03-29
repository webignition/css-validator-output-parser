<?php

namespace webignition\CssValidatorOutput\ExceptionOutput;

use webignition\CssValidatorOutput\ExceptionOutput\Type\Type;
use webignition\CssValidatorOutput\ExceptionOutput\Type\Value;

class Parser {
    
    /**
     *
     * @var string
     */
    private $rawOutput = '';
    
    
    /**
     *
     * @var \webignition\CssValidatorOutput\ExceptionOutput\ExceptionOutput
     */
    private $output;
    
    
    /**
     * 
     * @param string $validatorBodyContent
     * @return boolean
     */
    public static function is($validatorBodyContent) {
        if (substr_count($validatorBodyContent, '</observationresponse>')) {
            return false;
        }
        
        return preg_match('/java.*Exception:/', $validatorBodyContent) > 0;        
    }
    
    
    /**
     * 
     * @param string $rawOutput
     */
    public function setRawOutput($rawOutput) {        
        $this->rawOutput = trim($rawOutput);
        $this->output = null;
    }    
    

    
    /**
     * 
     * @return ExceptionOutput
     */
    public function getOutput() {        
        if (is_null($this->output)) {
            $this->output = new ExceptionOutput();
            $this->parse();
        }
        
        return $this->output;
    }
    
    
    /**
     * 
     * @return ExceptionOutput
     */
    private function parse() {
        if ($this->isFileNotFoundError()) {
            return $this->setType('http404');
        }
        
        if ($this->isHttpAuthProtocolExceptionOutput()) {
            return $this->setType('http401');
        }
        
        if ($this->isIllegalUrlError()) {
            return $this->setType('curl3');
        }           
        
        if ($this->isInternalServerError()) {
            return $this->setType('http500');
        }  
        
        if ($this->isSslExceptionOutput()) {       
            return $this->setType(Value::SSL_EXCEPTION);
        }                 
        
        if ($this->isUnknownMimeTypeError()) {
            return $this->setType(Value::UNKNOWN_MIME_TYPE);
        }  
        
        if ($this->isUnknownHostError()) {
            return $this->setType('curl6');
        }
        
        if ($this->isUnknownFileExceptionOutput()) {
            return $this->setType(Value::UNKNOWN_FILE);
        }
        
        return $this->setType(Value::UNKNOWN);
    }
    
    
    /**
     * 
     * @param string $type
     * @return \webignition\CssValidatorOutput\ExceptionOutput\ExceptionOutput
     */
    private function setType($type) {
        $this->output->setType(new Type($type));
        return $this->output;
    }
    
    
    /**
     * 
     * @return string
     */
    private function getFirstLine() {
        return substr($this->rawOutput, 0, strpos($this->rawOutput, "\n"));
    }    
    
    
    /**
     * 
     * @return boolean
     */
    private function isUnknownMimeTypeError() {        
        return preg_match('/Unknown mime type :/', $this->getFirstLine()) > 0;     
    }    

    
    /**
     * 
     * @return boolean
     */    
    private function isInternalServerError() {
        if (!$this->isFileNotFoundException()) {
            return false;
        }
        
        return preg_match('/Internal Server Error/', $this->getFirstLine()) > 0;
    }
    

    /**
     * 
     * @return boolean
     */    
    private function isFileNotFoundError() {
        if (!$this->isFileNotFoundException()) {
            return false;
        }
        
        return preg_match('/Not Found/', $this->getFirstLine()) > 0;
    }    
    
    /**
     * 
     * @return boolean
     */
    private function isFileNotFoundException() {        
        return preg_match('/^java\.io\.FileNotFoundException:/', $this->getFirstLine()) > 0;      
    }
    
    
    /**
     * 
     * @return boolean
     */    
    private function isUnknownHostError() {
        return preg_match('/^java\.net\.UnknownHostException:/', $this->getFirstLine()) > 0;
    }      
    
    
    /**
     * 
     * @return boolean
     */    
    private function isIllegalUrlError() {
        return $this->getFirstLine() == 'java.lang.IllegalArgumentException: protocol = http host = null';
    }
    
    
    /**
     * 
     * @return boolean
     */
    private function isSslExceptionOutput() {
        $signature = 'javax.net.ssl.SSLException';        
        return substr($this->getFirstLine(), 0, strlen($signature)) == $signature;
    } 
    
    
    /**
     * 
     * @return boolean
     */
    private function isHttpAuthProtocolExceptionOutput() {   
        return preg_match('/java\.net\.ProtocolException: (Basic|Digest)/', $this->getFirstLine()) > 0;
    }
    
    
    /**
     * 
     * @return boolean
     */
    private function isUnknownFileExceptionOutput() {   
        return preg_match('/java.lang.Exception: Unknown file/', $this->getFirstLine()) > 0;
    }    
    
}