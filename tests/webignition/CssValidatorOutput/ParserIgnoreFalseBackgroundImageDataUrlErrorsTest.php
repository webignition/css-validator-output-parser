<?php

use webignition\CssValidatorOutput\Parser;

class ParserIgnoreFalseBackgroundImageDataUrlErrorsTest extends BaseTest {

    
    public function testParserIgnoreFalseBackgroundImageDataUrlErrors() {
        $rawOutput = $this->getFixture('incorrect-data-url-background-image-errors.xml');
        
        $parser = new Parser();
        $parser->setRawOutput($rawOutput);
        
        $cssValidatorOutput = $parser->getOutput();
        
        $this->assertEquals(12, $cssValidatorOutput->getMessageCount());
        $this->assertEquals(12, $cssValidatorOutput->getErrorCount());
        $this->assertEquals(0, $cssValidatorOutput->getWarningCount());   
        
        
        $parser->setRawOutput($rawOutput);
        $parser->setIgnoreFalseBackgroundImageDataUrlMessages(true);
        
        $cssValidatorOutput = $parser->getOutput();
        
        $this->assertEquals(6, $cssValidatorOutput->getMessageCount());
        $this->assertEquals(6, $cssValidatorOutput->getErrorCount());
        $this->assertEquals(0, $cssValidatorOutput->getWarningCount());        
    }
    
  
}