<?php

use webignition\CssValidatorOutput\Message\Parser as Parser;

class CssValidatorOutputMessageParserTest extends BaseTest {
    
    public function setUp() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
    }       
    
    public function testParseErrorMessage() {
        $outputDom = new \DOMDocument();
        $outputDom->loadXML($this->getFixture('error-message.xml'));
        $messageElement = $outputDom->getElementsByTagName('message')->item(0);
        
        $parser = new Parser();
        $parser->setMessageElement($messageElement);
        
        /* @var $message \webignition\CssValidatorOutput\Message\Error */
        $message = $parser->getMessage();
   
        $this->assertInstanceOf('webignition\CssValidatorOutput\Message\Error', $message);
        $this->assertEquals('Parse Error
            *display: inline;', $message->getMessage());
        $this->assertEquals('audio, canvas, video', $message->getContext());
        $this->assertEquals(28, $message->getLineNumber());        
        $this->assertTrue($message->isError());        
        $this->assertEquals('http://example.com/css/bootstrap.css', $message->getRef());
    }
    

    public function testParseWarningMessage() {
        $outputDom = new \DOMDocument();
        $outputDom->loadXML($this->getFixture('warning-message.xml'));
        $messageElement = $outputDom->getElementsByTagName('message')->item(0);
        
        $parser = new Parser();
        $parser->setMessageElement($messageElement);
        
        /* @var $message \webignition\CssValidatorOutput\Message\Warning */
        $message = $parser->getMessage();
   
        $this->assertInstanceOf('webignition\CssValidatorOutput\Message\Warning', $message);
        $this->assertEquals("You should add a 'type' attribute with a value of 'text/css' to the 'link' element", $message->getMessage());
        $this->assertEquals('', $message->getContext());
        $this->assertEquals(5, $message->getLineNumber());        
        $this->assertTrue($message->isWarning());        
        $this->assertEquals(0, $message->getLevel());
    }    
   
}