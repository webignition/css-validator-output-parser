<?php
namespace webignition\Tests\CssValidatorOutput\Parser\GetOutput;

use webignition\Tests\CssValidatorOutput\BaseTest;
use webignition\CssValidatorOutput\Parser;
use webignition\CssValidatorOutput\CssValidatorOutput;

class LargeOutputTest extends BaseTest {     
    
    public function setUp() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
    }
    
    public function test895_890_5() {
        $rawOutput = $this->getFixture('output02.txt');
        
        $parser = new Parser();
        $parser->setRawOutput($rawOutput);
        
        $cssValidatorOutput = $parser->getOutput();
        
        $this->assertEquals(895, $cssValidatorOutput->getMessageCount());
        $this->assertEquals(890, $cssValidatorOutput->getErrorCount());
        $this->assertEquals(5, $cssValidatorOutput->getWArningCount());       
    }   
    

    public function test895_623_272() {
        $rawOutput = $this->getFixture('output03.txt');
        
        $parser = new Parser();
        $parser->setRawOutput($rawOutput);
        
        $cssValidatorOutput = $parser->getOutput();
        
        $this->assertEquals(895, $cssValidatorOutput->getMessageCount());
        $this->assertEquals(623, $cssValidatorOutput->getErrorCount());
        $this->assertEquals(272, $cssValidatorOutput->getWArningCount());       
    }    
    
    
    public function test1093_535_272() {
        $rawOutput = $this->getFixture('output04.txt');
        
        $parser = new Parser();
        $parser->setRawOutput($rawOutput);
        
        $cssValidatorOutput = $parser->getOutput();
        
        $this->assertEquals(1093, $cssValidatorOutput->getMessageCount());
        $this->assertEquals(689, $cssValidatorOutput->getErrorCount());
        $this->assertEquals(404, $cssValidatorOutput->getWArningCount());       
    }     
}