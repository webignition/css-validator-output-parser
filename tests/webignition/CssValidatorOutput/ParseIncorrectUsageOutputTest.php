<?php

use webignition\CssValidatorOutput\Parser as Parser;

class ParseIncorrectUsageOutputTest extends BaseTest {
    
    public function setUp() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
    }    
    
    public function testParseIncorrectUsage() {
        $rawOutput = $this->getFixture('incorrect-usage.txt');
        
        $parser = new Parser();
        $parser->setRawOutput($rawOutput);
        
        $cssValidatorOutput = $parser->getOutput();                
        
        $this->assertInstanceOf('webignition\CssValidatorOutput\CssValidatorOutput', $cssValidatorOutput);
        
        $this->assertTrue($cssValidatorOutput->getIsIncorrectUsageOutput());
        
        $options = $cssValidatorOutput->getOptions();        
        $this->assertNull($options);
        
        $datetime = $cssValidatorOutput->getDateTime();
        $this->assertNull($datetime);
        
        $this->assertEquals(0, $cssValidatorOutput->getMessageCount());
        $this->assertEquals(0, $cssValidatorOutput->getErrorCount());
        $this->assertEquals(0, $cssValidatorOutput->getWArningCount());      
    }
}