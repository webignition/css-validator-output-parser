<?php
namespace webignition\Tests\CssValidatorOutput\Parser\GetOutput;

use webignition\Tests\CssValidatorOutput\BaseTest;

class InvalidPCDATAOutputTest extends BaseTest {    
    
    public function testWithInvalidCharacterx11() {
        $this->assertTestYieldsGivenMessageErrorandWarningCount(array(
            'configuration' => array(
                'ignoreWarnings' => true
            ),
            'rawOutput' => $this->getFixture('invalid-pcdata.xml'),
            'errorCount' => 1,
            'warningCount' => 0
        ));    
    }
}