<?php
namespace webignition\Tests\CssValidatorOutput\Parser\GetOutput;

use webignition\Tests\CssValidatorOutput\BaseTest;
use webignition\CssValidatorOutput\Parser as Parser;

class ParseNoMessagesOutputTest extends BaseTest {
    
    public function setUp() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
    }    
    
    public function testParseUnknownMimeTypeError() {
        $rawOutput = $this->getFixture('no-messages.txt');
        
        $parser = new Parser();
        $parser->setRawOutput($rawOutput);
        
        $cssValidatorOutput = $parser->getOutput();                
        
        $this->assertInstanceOf('webignition\CssValidatorOutput\CssValidatorOutput', $cssValidatorOutput);
        
        $this->assertEquals(0, $cssValidatorOutput->getMessageCount());
        $this->assertEquals(0, $cssValidatorOutput->getErrorCount());
        $this->assertEquals(0, $cssValidatorOutput->getWarningCount());      
    }
}