<?php
namespace webignition\Tests\CssValidatorOutput\Parser\GetOutput;

use webignition\Tests\CssValidatorOutput\BaseTest;

class ParseNoMessagesOutputTest extends BaseTest {
    
    public function setUp() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
    }    
    
    public function testParseUnknownMimeTypeError() {
        $this->assertTestYieldsGivenMessageErrorandWarningCount(array(
            'rawOutput' => $this->getFixture('no-messages.txt'),
            'errorCount' => 0,
            'warningCount' => 0
        ));    
    }
}