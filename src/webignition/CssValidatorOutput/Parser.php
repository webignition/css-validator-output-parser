<?php

namespace webignition\CssValidatorOutput;

use webignition\CssValidatorOutput\Options\Parser as OptionsParser;
use webignition\CssValidatorOutput\Message\Parser as MessageParser;
use webignition\CssValidatorOutput\Message\Message;
use webignition\NormalisedUrl\NormalisedUrl;

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
     * @var array
     */
    private $refDomainsToIgnore = array();
    
    
    /**
     *
     * @var boolean
     */
    private $ignoreVendorExtensionIssues = false;
    
    
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
     * @param array $refDomainsToIgnore
     * @return \webignition\CssValidatorOutput\Parser
     */
    public function setRefDomainsToIgnore($refDomainsToIgnore) {
        if (!is_array($refDomainsToIgnore)) {
            $refDomainsToIgnore = array();
        }
        
        $this->refDomainsToIgnore = $refDomainsToIgnore;
        return $this;
    }
    
    
    /**
     * 
     * @return array
     */
    public function getRefDomainsToIgnore() {
        return $this->refDomainsToIgnore;
    }
    
    
    /**
     * 
     * @param boolean $ignoreVendorExtensionIssues
     * @return \webignition\CssValidatorOutput\Parser
     */
    public function setIgnoreVendorExtensionIssues($ignoreVendorExtensionIssues) {
        $this->ignoreVendorExtensionIssues = filter_Var($ignoreVendorExtensionIssues, FILTER_VALIDATE_BOOLEAN);
        return $this;
    }
    
    
    /**
     * 
     * @return boolean
     */
    public function getIgnoreVendorExtensionIssues() {
        return $this->ignoreVendorExtensionIssues;
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
            
            if ($this->hasRefDomainToIgnore($message)) {
                continue;
            }
            
            if ($this->getIgnoreVendorExtensionIssues() === true && $this->isVendorExtensionMessage($message)) {
                continue;
            }
            
            $this->output->addMessage($messageParser->getMessage());
        }
    }
    
    
    /**
     * 
     * @param \webignition\CssValidatorOutput\Message\Message $message
     * @return boolean
     */
    private function hasRefDomainToIgnore(Message $message) {
        if (!$message->isError()) {
            return false;
        }
        
        /* @var $message \webignition\CssValidatorOutput\Message\Error */        
        $messageRefUrl = new NormalisedUrl($message->getRef());
        
        return in_array((string)$messageRefUrl->getHost(), $this->refDomainsToIgnore);
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
    
    

    /**
     * 
     * @param \webignition\CssValidatorOutput\Message\Message $message
     * @return boolean
     */
    private function isVendorExtensionMessage(Message $message) {       
        $patterns = array(
            '/is an unknown vendor extension/', #
            '/^Property \-[a-z\-]+ doesn\&#39;t exist/', #
            '/^Unknown pseudo\-element or pseudo\-class [:]{1,2}\-[a-z\-]+/', #
            '/-webkit\-focus\-ring\-color is not a outline\-color value/',
            '/Sorry, the at\-rule @\-[a-z\-]+ is not implemented./'
        );
        
        
        
/**
string(57) "Sorry, the at-rule @-webkit-keyframes is not implemented."
string(54) "Sorry, the at-rule @-moz-keyframes is not implemented."
string(53) "Sorry, the at-rule @-ms-keyframes is not implemented."
string(52) "Sorry, the at-rule @-o-keyframes is not implemented."


 */        
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $message->getBody()) > 0) {
                return true;
            }
        }
        
        return false;
    }    
    
}