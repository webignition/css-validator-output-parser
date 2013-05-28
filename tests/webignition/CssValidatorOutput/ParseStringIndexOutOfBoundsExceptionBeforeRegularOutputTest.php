<?php

use webignition\CssValidatorOutput\Parser as Parser;

class ParseStringIndexOutOfBoundsExceptionBeforeRegularOutputTest extends BaseTest {
    
    public function setUp() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
    }    
    
    public function testParseStringIndexOutOfBoundsExceptionBeforeRegularOutput() {
        $rawOutput = $this->getFixture('string-index-out-of-bounds-exception.txt');
        
        $parser = new Parser();
        $parser->setRawOutput($rawOutput);
        
        $cssValidatorOutput = $parser->getOutput();                
        
        $this->assertInstanceOf('webignition\CssValidatorOutput\CssValidatorOutput', $cssValidatorOutput);        
        
        $this->assertEquals(3, $cssValidatorOutput->getMessageCount());
        $this->assertEquals(3, $cssValidatorOutput->getErrorCount());
        $this->assertEquals(0, $cssValidatorOutput->getWArningCount());      
    }
}