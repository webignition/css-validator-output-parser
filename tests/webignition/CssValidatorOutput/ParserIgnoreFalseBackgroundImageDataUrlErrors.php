<?php

use webignition\CssValidatorOutput\Parser;

class ParserIgnoreIncorrectBase64BackgroundImageUrlErrors extends BaseTest {

    
    public function ParserIgnoreFalseBackgroundImageDataUrlErrors() {        
        $rawOutput = $this->getFixture('incorrect-base64-background-image-errors.xml');
        
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