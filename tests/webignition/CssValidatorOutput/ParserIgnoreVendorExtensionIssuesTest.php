<?php

use webignition\CssValidatorOutput\Parser;

class ParserIgnoreVendorExtensionIssuesTest extends BaseTest {  
    
    public function setUp() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
    }
    
    public function testIgnoreVendorExtensionIssuesDefaultVextWarningTrue() {        
        $rawOutput = $this->getFixture('output06.txt');
        
        $parser = new Parser();
        $parser->setRawOutput($rawOutput);        
        
        $cssValidatorOutput = $parser->getOutput();
        $this->assertInstanceOf('webignition\CssValidatorOutput\CssValidatorOutput', $cssValidatorOutput);     
        
        $this->assertEquals(25, $cssValidatorOutput->getErrorCount());
        $this->assertEquals(95, $cssValidatorOutput->getWarningCount());
    }      
    
    
    public function testIgnoreVendorExtensionIssuesTrueVextWarningTrue() {        
        /* contains 25 errors, 14 of which are vext issues */
        $rawOutput = $this->getFixture('output06.txt');
        
        $parser = new Parser();
        $parser->setRawOutput($rawOutput);  
        $parser->setIgnoreVendorExtensionIssues(true);
        
        $cssValidatorOutput = $parser->getOutput();
        $this->assertInstanceOf('webignition\CssValidatorOutput\CssValidatorOutput', $cssValidatorOutput);     
        
        $this->assertEquals(11, $cssValidatorOutput->getErrorCount());
        $this->assertEquals(5, $cssValidatorOutput->getWarningCount());
    } 
    
    public function testIgnoreVendorExtensionIssuesFalseVextWarningTrue() {        
        /* contains 25 errors, 14 of which are vext issues */
        $rawOutput = $this->getFixture('output06.txt');
        
        $parser = new Parser();
        $parser->setRawOutput($rawOutput);  
        $parser->setIgnoreVendorExtensionIssues(false);
        
        $cssValidatorOutput = $parser->getOutput();
        $this->assertInstanceOf('webignition\CssValidatorOutput\CssValidatorOutput', $cssValidatorOutput);     
        
        $this->assertEquals(25, $cssValidatorOutput->getErrorCount());
        $this->assertEquals(95, $cssValidatorOutput->getWarningCount());
    }  
    
    
    public function testIgnoreVendorExtensionIssuesDefaultVextWarningFalse() {        
        $rawOutput = $this->getFixture('output07.txt');
        
        $parser = new Parser();
        $parser->setRawOutput($rawOutput);        
        
        $cssValidatorOutput = $parser->getOutput();
        $this->assertInstanceOf('webignition\CssValidatorOutput\CssValidatorOutput', $cssValidatorOutput);     
        
        $this->assertEquals(52, $cssValidatorOutput->getErrorCount());
        $this->assertEquals(5, $cssValidatorOutput->getWarningCount());
    }     
    
    
    public function testIgnoreVendorExtensionIssuesTrueVextWarningFalse() {        
        $rawOutput = $this->getFixture('output07.txt');
        
        $parser = new Parser();
        $parser->setRawOutput($rawOutput);        
        $parser->setIgnoreVendorExtensionIssues(true);
        
        $cssValidatorOutput = $parser->getOutput();
        $this->assertInstanceOf('webignition\CssValidatorOutput\CssValidatorOutput', $cssValidatorOutput);     
        
        $this->assertEquals(7, $cssValidatorOutput->getErrorCount());
        $this->assertEquals(5, $cssValidatorOutput->getWarningCount());
    }     
    
    public function testIgnoreVendorExtensionIssuesFalseVextWarningFalse() {        
        $rawOutput = $this->getFixture('output07.txt');
        
        $parser = new Parser();
        $parser->setRawOutput($rawOutput);        
        $parser->setIgnoreVendorExtensionIssues(false);
        
        $cssValidatorOutput = $parser->getOutput();
        $this->assertInstanceOf('webignition\CssValidatorOutput\CssValidatorOutput', $cssValidatorOutput);     
        
        $this->assertEquals(52, $cssValidatorOutput->getErrorCount());
        $this->assertEquals(5, $cssValidatorOutput->getWarningCount());
    }    
}