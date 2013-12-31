<?php
namespace webignition\Tests\CssValidatorOutput\Parser\GetOutput;

use webignition\Tests\CssValidatorOutput\BaseTest;
use webignition\CssValidatorOutput\Parser\Parser as CssValidatorOutputParser;

class IgnoreFalseImageDataUrlErrorsTest extends BaseTest {
  
    public function testDisabled() {
        $rawOutput = $this->getFixture('incorrect-data-url-background-image-errors.xml');
        
        $parser = new CssValidatorOutputParser();
        $parser->setRawOutput($rawOutput);
        
        $cssValidatorOutput = $parser->getOutput();
        
        $this->assertEquals(9, $cssValidatorOutput->getMessageCount());
        $this->assertEquals(9, $cssValidatorOutput->getErrorCount());
        $this->assertEquals(0, $cssValidatorOutput->getWarningCount());       
    }    
    
    public function testEnabled() {
        $rawOutput = $this->getFixture('incorrect-data-url-background-image-errors.xml');
        
        $parser = new CssValidatorOutputParser();
        $parser->setRawOutput($rawOutput);
        $parser->setIgnoreFalseImageDataUrlMessages(true);
        
        $cssValidatorOutput = $parser->getOutput();
        
        $this->assertEquals(0, $cssValidatorOutput->getMessageCount());
        $this->assertEquals(0, $cssValidatorOutput->getErrorCount());
        $this->assertEquals(0, $cssValidatorOutput->getWarningCount());
    }
    
  
}