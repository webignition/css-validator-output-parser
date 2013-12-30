<?php

use webignition\CssValidatorOutput\Parser as Parser;

class ParseHttpAuthProtocolExeceptionOutputTest extends BaseTest {
    
    public function setUp() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
    }    
    
    public function testParseUnknownHostErrorOutput() {
        $rawOutput = $this->getFixture('http-auth-protocol-exception.txt');
        
        $parser = new Parser();
        $parser->setRawOutput($rawOutput);
        
        $cssValidatorOutput = $parser->getOutput();                
        
        $this->assertInstanceOf('webignition\CssValidatorOutput\CssValidatorOutput', $cssValidatorOutput);        
        
        $this->assertTrue($cssValidatorOutput->getIsHttpAuthProtocolErrorOutput());    
    }
}