<?php

namespace webignition\CssValidatorOutput\Message;

class Parser {
    
    /**
     *
     * @var \DOMElement
     */
    private $messageElement = '';
    
    
    /**
     *
     * @var Message
     */
    private $message;
    
    /**
     * 
     * @param \DOMElement $messageElement
     */
    public function setMessageElement(\DOMElement $messageElement) {
        $this->messageElement = $messageElement;
        $this->message = null;
    }
    
    
    /**
     * 
     * @return Message
     */
    public function getMessage() {
        if (is_null($this->message)) {
            $this->parse();
        }
        
        return $this->message;
    }
    
    
    private function parse() {
        switch ($this->messageElement->getAttribute('type')) {
            case 'error':
                $this->message = new Error();
                $this->message->setRef($this->messageElement->getAttribute('ref'));
                break;
            
            case 'warning':
                $this->message = new Warning();
                $this->message->setLevel($this->messageElement->getAttribute('level'));
                break;
            
            default:
                return false;
        }
        
        $contextNode = $this->messageElement->getElementsByTagName('context')->item(0);
        
        $this->message->setContext($contextNode->nodeValue);
        $this->message->setLineNumber($contextNode->getAttribute('line'));        
        $this->message->setBody(trim($this->messageElement->getElementsByTagName('title')->item(0)->nodeValue));
    }
    
}
