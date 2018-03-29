<?php

namespace webignition\Tests\CssValidatorOutput\Message;

use webignition\Tests\CssValidatorOutput\BaseTest;

class GetErrorsByUrlTest extends BaseTest {
    
    public function testGetErrorsByUrl() {
        $error1 = new \webignition\CssValidatorOutput\Message\Error();
        $error1->setRef('http://foo.example.com');
        $error1->setMessage('error 1 message');
        
        $error2 = new \webignition\CssValidatorOutput\Message\Error();
        $error2->setRef('http://bar.example.com');
        $error2->setMessage('error 2 message');
        
        $error3 = new \webignition\CssValidatorOutput\Message\Error();
        $error3->setRef('http://bar.example.com');
        $error3->setMessage('error 3 message');
        
        $output = new \webignition\CssValidatorOutput\CssValidatorOutput();
        $output->addMessage($error1);
        $output->addMessage($error2);
        $output->addMessage($error3);
        
        $errorsForFoo = $output->getErrorsByUrl('http://foo.example.com');
        $errorsForBar = $output->getErrorsByUrl('http://bar.example.com');
        
        $this->assertEquals(1, count($errorsForFoo));
        $this->assertEquals(2, count($errorsForBar));
        
        $this->assertEquals('error 1 message', $errorsForFoo[0]->getMessage());
        $this->assertEquals('error 2 message', $errorsForBar[0]->getMessage());
        $this->assertEquals('error 3 message', $errorsForBar[1]->getMessage());
    } 
   
}