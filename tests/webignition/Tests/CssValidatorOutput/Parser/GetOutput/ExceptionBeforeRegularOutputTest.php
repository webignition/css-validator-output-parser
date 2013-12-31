<?php
namespace webignition\Tests\CssValidatorOutput\Parser\GetOutput;

use webignition\Tests\CssValidatorOutput\BaseTest;

class StringIndexOutOfBoundsExceptionBeforeRegularOutputTest extends BaseTest {
    
    public function setUp() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
    }    
    
    public function testParseStringIndexOutOfBoundsExceptionBeforeRegularOutput() {
        $this->assertTestYieldsGivenMessageErrorandWarningCount(array(
            'rawOutput' => $this->getFixture('string-index-out-of-bounds-exception.txt'),
            'errorCount' => 3,
            'warningCount' => 0
        ));     
    }
}