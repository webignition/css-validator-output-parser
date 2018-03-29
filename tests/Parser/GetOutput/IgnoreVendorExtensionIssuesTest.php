<?php
namespace webignition\Tests\CssValidatorOutput\Parser\GetOutput;

use webignition\Tests\CssValidatorOutput\BaseTest;

class IgnoreVendorExtensionIssuesTest extends BaseTest {  
    
    public function setUp() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
    }
    
    public function testIgnoreVendorExtensionIssuesDefaultVextWarningTrue() {        
        $this->assertTestYieldsGivenMessageErrorandWarningCount(array(
            'rawOutput' => $this->getFixture('output06.txt'),
            'errorCount' => 25,
            'warningCount' => 95
        ));
    }      
    
    public function testIgnoreVendorExtensionIssuesTrueVextWarningTrue() {        
        $this->assertTestYieldsGivenMessageErrorandWarningCount(array(
            'configuration' => array(
                'ignoreVendorExtensionIssues' => true
            ),
            'rawOutput' => $this->getFixture('output06.txt'),
            'errorCount' => 11,
            'warningCount' => 5
        ));        
    } 
    
    public function testIgnoreVendorExtensionIssuesFalseVextWarningTrue() {        
        $this->assertTestYieldsGivenMessageErrorandWarningCount(array(
            'configuration' => array(
                'ignoreVendorExtensionIssues' => false
            ),
            'rawOutput' => $this->getFixture('output06.txt'),
            'errorCount' => 25,
            'warningCount' => 95
        ));             
    }  
    
    
    public function testIgnoreVendorExtensionIssuesDefaultVextWarningFalse() {        
        $this->assertTestYieldsGivenMessageErrorandWarningCount(array(
            'rawOutput' => $this->getFixture('output07.txt'),
            'errorCount' => 52,
            'warningCount' => 5
        ));
    }     
    
    
    public function testIgnoreVendorExtensionIssuesTrueVextWarningFalse() {        
        $this->assertTestYieldsGivenMessageErrorandWarningCount(array(
            'configuration' => array(
                'ignoreVendorExtensionIssues' => true
            ),
            'rawOutput' => $this->getFixture('output07.txt'),
            'errorCount' => 7,
            'warningCount' => 5
        ));
    }     
    
    public function testIgnoreVendorExtensionIssuesFalseVextWarningFalse() {        
        $this->assertTestYieldsGivenMessageErrorandWarningCount(array(
            'configuration' => array(
                'ignoreVendorExtensionIssues' => false
            ),
            'rawOutput' => $this->getFixture('output07.txt'),
            'errorCount' => 52,
            'warningCount' => 5
        ));
    } 
    
    
    public function testIgnoreVendorExtensionAtRules() {        
        $this->assertTestYieldsGivenMessageErrorandWarningCount(array(
            'configuration' => array(
                'ignoreVendorExtensionIssues' => true
            ),
            'rawOutput' => $this->getFixture('vendor-specific-at-rules.txt'),
            'errorCount' => 1,
            'warningCount' => 0
        ));
    }     
}