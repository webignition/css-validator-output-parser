<?php

use webignition\CssValidatorOutput\Options\Parser as CssValidatorOutputOptionsParser;

class CssValidatorOutputOptionsParserTest extends BaseTest {
    
    public function setUp() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
    }       
    
    public function testParseValidOptions() {
        $rawOutput = $this->getFixture('valid-options.txt');
        
        $optionsParser = new CssValidatorOutputOptionsParser();
        $optionsParser->setOptionsOutput($rawOutput);
        $options = $optionsParser->getOptions();
        
        $this->assertInstanceOf('webignition\CssValidatorOutput\Options\Options', $options);
        
        $this->assertFalse($options->getVendorExtensionIssuesAsWarnings());
        $this->assertEquals('ucn', $options->getOutputFormat());        
        $this->assertEquals('en', $options->getLanguage());
        $this->assertEquals(2, $options->getWarningLevel());
        $this->assertEquals('all', $options->getMedium());
        $this->assertEquals('css3', $options->getProfile());
    }
    
    
    public function testParseInvalidOptions() {
        $rawOutput = $this->getFixture('invalid-options.txt');
        
        $optionsParser = new CssValidatorOutputOptionsParser();
        $optionsParser->setOptionsOutput($rawOutput);
        $options = $optionsParser->getOptions();
        
        $this->assertNull($options);
    }
    
    
    public function testParseWithNoOutputSpecified() {        
        $optionsParser = new CssValidatorOutputOptionsParser();
        $options = $optionsParser->getOptions();
        
        $this->assertNull($options);
    }    
}