<?php
namespace webignition\Tests\CssValidatorOutput\Parser\GetOutput;

use webignition\Tests\CssValidatorOutput\BaseTest;

class IgnoreFalseImageDataUrlErrorsTest extends BaseTest {
  
    public function testDisabled() { 
        $this->assertTestYieldsGivenMessageErrorandWarningCount(array(
            'rawOutput' => $this->getFixture('incorrect-data-url-background-image-errors.xml'),
            'errorCount' => 9,
            'warningCount' => 0
        ));     
    }    
    
    public function testEnabled() {        
        $this->assertTestYieldsGivenMessageErrorandWarningCount(array(
            'rawOutput' => $this->getFixture('incorrect-data-url-background-image-errors.xml'),
            'configuration' => array(
                'ignoreFalseImageDataUrlMessages' => true
            ),
            'errorCount' => 0,
            'warningCount' => 0
        ));   
    }
    
  
}