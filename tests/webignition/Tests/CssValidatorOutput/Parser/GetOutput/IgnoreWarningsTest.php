<?php
namespace webignition\Tests\CssValidatorOutput\Parser\GetOutput;

use webignition\Tests\CssValidatorOutput\BaseTest;
use webignition\CssValidatorOutput\Parser;

class IgnoreWarningsTest extends BaseTest {
    
    private $rawOutput;
    
    public function setUp() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
        $this->rawOutput = $this->getFixture('output01.txt');
    }
    
    public function testIgnoreWarningsTrue() {        
        $parser = new Parser();
        $parser->setRawOutput($this->rawOutput);        
        $parser->setIgnoreWarnings(true);
        
        $cssValidatorOutput = $parser->getOutput();
        $this->assertInstanceOf('webignition\CssValidatorOutput\CssValidatorOutput', $cssValidatorOutput);     
        
        $this->assertEquals(3, $cssValidatorOutput->getErrorCount());
        $this->assertEquals(0, $cssValidatorOutput->getWarningCount());
    }    

    public function testIgnoreWarningsFalse() {        
        $parser = new Parser();
        $parser->setRawOutput($this->rawOutput);        
        $parser->setIgnoreWarnings(false);
        
        $cssValidatorOutput = $parser->getOutput();
        $this->assertInstanceOf('webignition\CssValidatorOutput\CssValidatorOutput', $cssValidatorOutput);     
        
        $this->assertEquals(3, $cssValidatorOutput->getErrorCount());
        $this->assertEquals(2, $cssValidatorOutput->getWarningCount());
    }    

    
    public function testIgnoreWarningsDefault() {        
        $parser = new Parser();
        $parser->setRawOutput($this->rawOutput);
        
        $cssValidatorOutput = $parser->getOutput();
        $this->assertInstanceOf('webignition\CssValidatorOutput\CssValidatorOutput', $cssValidatorOutput);     
        
        $this->assertEquals(3, $cssValidatorOutput->getErrorCount());
        $this->assertEquals(2, $cssValidatorOutput->getWarningCount());
    }        
}