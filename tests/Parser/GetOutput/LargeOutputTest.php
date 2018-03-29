<?php
namespace webignition\Tests\CssValidatorOutput\Parser\GetOutput;

use webignition\Tests\CssValidatorOutput\BaseTest;

class LargeOutputTest extends BaseTest {     
    
    public function setUp() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
    }
    
    public function test895_890_5() {
        $this->assertTestYieldsGivenMessageErrorandWarningCount(array(
            'rawOutput' => $this->getFixture('output02.txt'),
            'errorCount' => 890,
            'warningCount' => 5
        ));      
    }   
    

    public function test895_623_272() {
        $this->assertTestYieldsGivenMessageErrorandWarningCount(array(
            'rawOutput' => $this->getFixture('output03.txt'),
            'errorCount' => 623,
            'warningCount' => 272
        ));    
    }    
    
    
    public function test1093_535_272() {
        $this->assertTestYieldsGivenMessageErrorandWarningCount(array(
            'rawOutput' => $this->getFixture('output04.txt'),
            'errorCount' => 689,
            'warningCount' => 404
        ));     
    }     
}