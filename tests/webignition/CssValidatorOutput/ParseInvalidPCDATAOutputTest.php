<?php

use webignition\CssValidatorOutput\Parser;

class ParseInvalidPCDATAOutputTest extends BaseTest {    
    
    public function testWithInvalidCharacterx11() {
        $rawOutput = $this->getFixture('invalid-pcdata.xml');
        
        $parser = new Parser();
        $parser->setRawOutput($rawOutput);
        
        $cssValidatorOutput = $parser->getOutput();
        
        $this->assertEquals(1, $cssValidatorOutput->getMessageCount());
        $this->assertEquals(1, $cssValidatorOutput->getErrorCount());
        $this->assertEquals(0, $cssValidatorOutput->getWarningCount());       
    }
}