<?php

use webignition\CssValidatorOutput\Parser as Parser;

class ParseIllegalArgumentException extends BaseTest {
    
    public function setUp() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
    }    
    
    public function testParseIllegalArgumentExceptionOutput() {
        $rawOutput = $this->getFixture('illegal-argument-exception.txt');
        
        $parser = new Parser();
        $parser->setRawOutput($rawOutput);
        
        $cssValidatorOutput = $parser->getOutput();                
        
        $this->assertInstanceOf('webignition\CssValidatorOutput\CssValidatorOutput', $cssValidatorOutput);
        
        $this->assertTrue($cssValidatorOutput->getIsUnknownExceptionError());
        $this->assertEquals(0, $cssValidatorOutput->getMessageCount());
        $this->assertEquals(0, $cssValidatorOutput->getErrorCount());
        $this->assertEquals(0, $cssValidatorOutput->getWarningCount());      
    }
}