<?php
namespace webignition\Tests\CssValidatorOutput\Parser\GetOutput;

use webignition\Tests\CssValidatorOutput\BaseTest;
use webignition\CssValidatorOutput\Parser as Parser;

class UnknownMimeTypeErrorTest extends BaseTest {
    
    public function setUp() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
    }    
    
    public function testParseUnknownMimeTypeError() {
        $rawOutput = $this->getFixture('unknown-mime-type-error.txt');
        
        $parser = new Parser();
        $parser->setRawOutput($rawOutput);
        
        $cssValidatorOutput = $parser->getOutput();                
        
        $this->assertInstanceOf('webignition\CssValidatorOutput\CssValidatorOutput', $cssValidatorOutput);
        
        $this->assertTrue($cssValidatorOutput->getIsUnknownMimeTypeError());
        
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