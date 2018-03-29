<?php

namespace webignition\Tests\CssValidatorOutput\Message;

use webignition\Tests\CssValidatorOutput\BaseTest;

class ErrorTest extends BaseTest {
    
    public function setUp() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
    }       
    
    public function testSetGetRef() {
        $error = new \webignition\CssValidatorOutput\Message\Error();
        $error->setRef('http://example.com');
        
        $this->assertEquals('http://example.com', $error->getRef());
    }  
    
    
    public function testGetDefaultType() {
        $error = new \webignition\CssValidatorOutput\Message\Error();        
        $this->assertTrue($error->isError());
    }
    
    public function tetGetSerializedType() {
        $error = new \webignition\CssValidatorOutput\Message\Error();        
        $this->assertEquals('error', $error->getSerializedType());        
    }
   
}