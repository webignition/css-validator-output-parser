<?php
namespace webignition\Tests\CssValidatorOutput\Parser\GetOutput;

use webignition\Tests\CssValidatorOutput\BaseTest;

class IncorrectUsageOutputTest extends BaseTest {
    
    public function setUp() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
    }    
    
    public function testParseIncorrectUsage() {
        $parser = $this->getParser(array(
            'rawOutput' => $this->getFixture('incorrect-usage.txt')
        ));
        
        $cssValidatorOutput = $parser->getOutput();                
        
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