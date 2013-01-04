<?php

class CssValidatorOutputErrorTest extends BaseTest {
    
    public function setUp() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
    }       
    
    public function testSetGetRef() {
        $error = new webignition\CssValidatorOutput\Message\Error();
        $error->setRef('http://example.com');
        
        $this->assertEquals('http://example.com', $error->getRef());
    }  
    
    
    public function testGetDefaultType() {
        $error = new webignition\CssValidatorOutput\Message\Error();        
        $this->assertTrue($error->isError());
    }    
   
}