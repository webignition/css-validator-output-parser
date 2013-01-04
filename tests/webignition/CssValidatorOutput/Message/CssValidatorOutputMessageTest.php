<?php

class CssValidatorOutputMessageTest extends BaseTest {
    
    /**
     *
     * @var \webignition\CssValidatorOutput\Message\Message 
     */
    private $message;
    
    public function setUp() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
        $this->message = $this->getMockForAbstractClass('webignition\CssValidatorOutput\Message\Message');
    }    
    
    public function testDefaultBody() {      
        $this->assertEquals('', $this->message->getMessage());
    }
    
    
    public function testMessageBody() {
        $this->message->setMessage('body content');        
        $this->assertEquals('body content', $this->message->getMessage());
    }
    
    
    public function testDefaultContext() {
        $this->assertEquals('', $this->message->getContext());        
    }
    
    public function testContext() {
        $this->message->setContext('context content');         
        $this->assertEquals('context content', $this->message->getContext());        
    }
    
    public function testDefaultLineNumber() {
        $this->assertEquals(0, $this->message->getLineNumber()); 
    }    

    public function testSetNegativeLineNumber() {
        $this->message->setLineNumber(-1);
        $this->assertEquals(0, $this->message->getLineNumber()); 
    }    
    
    public function testSetPositiveLineNumber() {
        $this->message->setLineNumber(1);
        $this->assertEquals(1, $this->message->getLineNumber()); 
    }    
    
    public function testSetNonIntegerLineNumber() {
        $this->message->setLineNumber('foobar');
        $this->assertEquals(0, $this->message->getLineNumber()); 
    }   
}