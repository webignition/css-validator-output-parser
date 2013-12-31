<?php

namespace webignition\CssValidatorOutput;

use webignition\CssValidatorOutput\Sanitizer;
use webignition\CssValidatorOutput\Options\Parser as OptionsParser;
use webignition\CssValidatorOutput\Message\Parser as MessageParser;
use webignition\CssValidatorOutput\Message\Message;
use webignition\NormalisedUrl\NormalisedUrl;
use webignition\Url\Url;

use webignition\CssValidatorOutput\ExceptionOutput\Parser as ExceptionOutputParser;

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
     * @var boolean
     */
    private $ignoreFalseImageDataUrlMessages = false;
    
    
    /**
     * 
     * @param boolean $ignoreFalseBase64BackgroundImageMessages
     */
    public function setIgnoreFalseImageDataUrlMessages($ignoreFalseImageDataUrlMessages) {
        $this->ignoreFalseImageDataUrlMessages = $ignoreFalseImageDataUrlMessages;
    }
    
    
    /**
     * @return bool
     */
    public function getIgnoreFalseImageDataUrlMessages() {
        return $this->ignoreFalseImageDataUrlMessages;
    }    
    
    
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
        $sanitizer = new Sanitizer();        
        $this->rawOutput = trim($sanitizer->getSanitizedOutput($rawOutput));
        $this->output = null;
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
        
        if (ExceptionOutputParser::is($body)) {
            $exceptionOutputParser = new ExceptionOutputParser();
            $exceptionOutputParser->setRawOutput($body);
            
            $this->output->setException($exceptionOutputParser->getOutput());
            return;
        }

        if ($this->isIncorrectUsageOutput($header)) {
            $this->output->setIsIncorrectUsageOutput(true);
            return;
        }
        
        $optionsParser = new OptionsParser();
        $optionsParser->setOptionsOutput($header);
        
        $messageParser = new MessageParser();
        
        $this->output->setOptions($optionsParser->getOptions());
        
        $bodyDom = new \DOMDocument();
        $bodyDom->loadXML($this->extractXmlContentFromBody($body));
        
        $container = $bodyDom->getElementsByTagName('observationresponse')->item(0);
        
        if ($this->isPassedNoMessagesOutput($container)) {
            return;
        }
        
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
            
            if ($this->getIgnoreFalseImageDataUrlMessages() && $this->isFalseImageDataUrlMessage($message)) {
                continue;
            }
            
            $this->output->addMessage($messageParser->getMessage());
        }
    }
    
    private function extractXmlContentFromBody($body) {
        $bodyLines = explode("\n", $body);
        
        $xmlContentStartLineNumber = $this->getXmlContentStartLineNumber($bodyLines);
        if ($xmlContentStartLineNumber === -1) {
            return '';
        }
        
        return implode("\n", array_slice($bodyLines, $this->getXmlContentStartLineNumber($bodyLines)));
    }
    
    
    private function getXmlContentStartLineNumber($bodyLines) {
        $xmlPremableStart = '<?xml';
        
        foreach ($bodyLines as $lineIndex => $line) {
            if (substr($line, 0, strlen($xmlPremableStart)) == $xmlPremableStart) {
                return $lineIndex;
            }
        }
        
        return -1;
    }
    
    
    /**
     * 
     * @param \webignition\CssValidatorOutput\Message\Message $message
     * @return boolean
     */
    private function isFalseImageDataUrlMessage(Message $message) {
        $propertyNames = array(
            'background-image',
            'background',
            'list-style-image'
        );
        
        if (preg_match('/Value Error\s*:\s*('.  implode('|', $propertyNames).')\s\(null.*\.html#propdef-('.  implode('|', $propertyNames).')\)/', $message->getMessage())) {            
            $messageLines = explode("\n", $message->getMessage());
            $firstMessageLine = trim($messageLines[1]);
            
            if (preg_match('/\(data:image\/[a-z0-9]{3};base64,/', $firstMessageLine) && $this->stringEndsWith($firstMessageLine, 'is an incorrect URL')) {
                return true;
            }
        }
    }
    
    
    /**
     * 
     * @param string $string
     * @param string $ending
     * @return boolean
     */
    private function stringEndsWith($string, $ending) {
        return substr($string, strlen($string) - strlen($ending)) == $ending;
    }
    
    
    /**
     * 
     * @param \DomElement $outputContainer
     * @return boolean
     */
    private function isPassedNoMessagesOutput(\DomElement $outputContainer) {
        $statusElements = $outputContainer->getElementsByTagName('status');
        if ($statusElements->length === 0) {
            return false;
        }
        
        $statusElement = $statusElements->item(0);
        if (!$statusElement->hasAttribute('value')) {
            return false;
        }        
        
        return ($statusElements->item(0)->getAttribute('value')) == 'passed';
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
        if ($message->getRef() == '') {
            return false;
        }        
               
        $messageRefUrl = new Url($message->getRef());        
        foreach ($this->getRefDomainsToIgnore() as $refDomainToIgnore) {                       
            if ($messageRefUrl->hasHost() && $messageRefUrl->getHost()->isEquivalentTo(new \webignition\Url\Host\Host($refDomainToIgnore))) {
                return true;
            }
        }
        
        return false;
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
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $message->getMessage()) > 0) {
                return true;
            }
        }
        
        return false;
    }    
    
}