<?php

namespace webignition\CssValidatorOutput;

use webignition\CssValidatorOutput\Options\Parser as OptionsParser;
use webignition\CssValidatorOutput\Message\Parser as MessageParser;

class Parser {
    
    /**
     *
     * @var string
     */
    private $rawOutput = '';
    
    
    /**
     *
     * @var CssValidatorOutput
     */
    private $output;
    
    
    /**
     *
     * @var boolean
     */
    private $ignoreWarnings = false;
    
    
    /**
     * 
     * @param boolean $ignoreWarnings
     * @return \webignition\CssValidatorOutput\CssValidatorOutput
     */
    public function setIgnoreWarnings($ignoreWarnings) {
        $this->ignoreWarnings = filter_var($ignoreWarnings, FILTER_VALIDATE_BOOLEAN);
        return $this;
    }
    
    
    /**
     * 
     * @return boolean
     */
    public function getIgnoreWarnings() {
        return $this->ignoreWarnings;
    }    
    
    
    /**
     * 
     * @param string $rawOutput
     */
    public function setRawOutput($rawOutput) {
        $this->rawOutput = trim($rawOutput);
    }
    
    
    /**
     * 
     * @return CssValidatorOutput
     */
    public function getOutput() {
        if (is_null($this->output)) {
            $this->output = new CssValidatorOutput();
            $this->parse();
        }
        
        return $this->output;
    }
    
    
    private function parse() { 
        $headerBodyParts = explode("\n", $this->rawOutput, 2);
        $header = trim($headerBodyParts[0]);
        $body = trim($headerBodyParts[1]);      
        
        if ($this->isIncorrectUsageOutput($header)) {
            $this->output->setIsIncorrectUsageOutput(true);
            return;
        }
        
        if ($this->isUnknownMimeTypeError($body)) {
            $this->output->setIsUnknownMimeTypeError(true);
        }            
        
        $optionsParser = new OptionsParser();
        $optionsParser->setOptionsOutput($header);
        
        $messageParser = new MessageParser();
        
        $this->output->setOptions($optionsParser->getOptions());
        
        if ($this->output->getIsUnknownMimeTypeError()) {
            return;
        }
        
        $bodyDom = new \DOMDocument();
        $bodyDom->loadXML($body);
        
        $container = $bodyDom->getElementsByTagName('observationresponse')->item(0);
        
        $this->output->setSourceUrl($container->getAttribute('ref'));
        $this->output->setDateTime(new \DateTime($container->getAttribute('date')));
        
        $messageElements = $container->getElementsByTagName('message');
        
        foreach ($messageElements as $messageElement) {
            /* @var $messageElement \DomElement */
            $messageParser->setMessageElement($messageElement);            
            
            /* @var $message \webignition\CssValidatorOutput\Message\Message */
            $message = $messageParser->getMessage();
            if ($message->isWarning() && $this->getIgnoreWarnings() === true) {
                continue;
            }
            
            $this->output->addMessage($messageParser->getMessage());
        }
    }
    
    
    /**
     * 
     * @param string $header
     * @return boolean
     */
    private function isIncorrectUsageOutput($header) {
        return preg_match('/^Usage/', $header) > 0;
    }
    
    
    /**
     * 
     * @param string $body
     * @return boolean
     */
    private function isUnknownMimeTypeError($body) {
        $bodyFirstLine = substr($body, 0, strpos($body, "\n"));
        
        return preg_match('/Unknown mime type :/', $bodyFirstLine) > 0;     
    }
    
}