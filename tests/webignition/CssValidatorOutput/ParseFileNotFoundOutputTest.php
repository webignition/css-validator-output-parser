<?php

use webignition\CssValidatorOutput\Parser as Parser;

class ParseFileNotFoundOutputTest extends BaseTest {
    
    public function setUp() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
    }    
    
    public function testParseFileNotFoundOutput() {
        $rawOutput = $this->getFixture('file-not-found-output.txt');
        
        $parser = new Parser();
        $parser->setRawOutput($rawOutput);
        
        $cssValidatorOutput = $parser->getOutput();                
        
        $this->assertInstanceOf('webignition\CssValidatorOutput\CssValidatorOutput', $cssValidatorOutput);        
        
        $this->assertTrue($cssValidatorOutput->getIsFileNotFoundErrorOutput());
        
        $options = $cssValidatorOutput->getOptions();        
        $this->assertInstanceOf('webignition\CssValidatorOutput\Options\Options', $options);

        $this->assertFalse($options->getVendorExtensionIssuesAsWarnings());
        $this->assertEquals('ucn', $options->getOutputFormat());        
        $this->assertEquals('en', $options->getLanguage());
        $this->assertEquals(2, $options->getWarningLevel());
        $this->assertEquals('all', $options->getMedium());
        $this->assertEquals('css3', $options->getProfile());
        
        $datetime = $cssValidatorOutput->getDateTime();
        $this->assertNull($datetime);
        
        $this->assertEquals(0, $cssValidatorOutput->getMessageCount());
        $this->assertEquals(0, $cssValidatorOutput->getErrorCount());
        $this->assertEquals(0, $cssValidatorOutput->getWArningCount());      
    }
}