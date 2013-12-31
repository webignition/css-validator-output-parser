<?php
namespace webignition\Tests\CssValidatorOutput\Parser\GetOutput;

use webignition\Tests\CssValidatorOutput\BaseTest;
use webignition\CssValidatorOutput\Parser\Parser as CssValidatorOutputParser;

class InvalidPCDATAOutputTest extends BaseTest {    
    
    public function testWithInvalidCharacterx11() {
        $rawOutput = $this->getFixture('invalid-pcdata.xml');
        
        $parser = new CssValidatorOutputParser();
        $parser->setRawOutput($rawOutput);
        
        $cssValidatorOutput = $parser->getOutput();
        
        $this->assertEquals(1, $cssValidatorOutput->getMessageCount());
        $this->assertEquals(1, $cssValidatorOutput->getErrorCount());
        $this->assertEquals(0, $cssValidatorOutput->getWarningCount());       
    }
}