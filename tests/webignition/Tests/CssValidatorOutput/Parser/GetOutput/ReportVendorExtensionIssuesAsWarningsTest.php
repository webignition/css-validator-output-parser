<?php
namespace webignition\Tests\CssValidatorOutput\Parser\GetOutput;

use webignition\Tests\CssValidatorOutput\BaseTest;

class ReportVendorExtensionIssuesAsWarningsTest extends BaseTest {  
    
    public function setUp() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
    }    
    
    public function testIgnoreVendorExtensionAtRules() {        
        $this->assertTestYieldsGivenMessageErrorandWarningCount(array(
            'configuration' => array(
                'reportVendorExtensionIssuesAsWarnings' => true
            ),
            'rawOutput' => $this->getFixture('vendor-specific-at-rules.txt'),
            'errorCount' => 1,
            'warningCount' => 12
        ));
    }     
}