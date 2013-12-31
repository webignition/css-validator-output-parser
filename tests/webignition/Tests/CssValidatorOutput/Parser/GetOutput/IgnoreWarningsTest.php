<?php
namespace webignition\Tests\CssValidatorOutput\Parser\GetOutput;

use webignition\Tests\CssValidatorOutput\BaseTest;

class IgnoreWarningsTest extends BaseTest {
    
    private $rawOutput;
    
    public function setUp() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
        $this->rawOutput = $this->getFixture('output01.txt');
    }
    
    public function testIgnoreWarningsTrue() {        
        $this->assertTestYieldsGivenMessageErrorandWarningCount(array(
            'configuration' => array(
                'ignoreWarnings' => true
            ),
            'rawOutput' => $this->rawOutput,
            'errorCount' => 3,
            'warningCount' => 0
        ));
    }    

    public function testIgnoreWarningsFalse() {        
        $this->assertTestYieldsGivenMessageErrorandWarningCount(array(
            'configuration' => array(
                'ignoreWarnings' => false
            ),
            'rawOutput' => $this->rawOutput,
            'errorCount' => 3,
            'warningCount' => 2
        ));        
    }    

    
    public function testIgnoreWarningsDefault() {        
        $this->assertTestYieldsGivenMessageErrorandWarningCount(array(
            'rawOutput' => $this->rawOutput,
            'errorCount' => 3,
            'warningCount' => 2
        ));
    }        
}