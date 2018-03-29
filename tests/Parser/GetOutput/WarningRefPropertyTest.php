<?php
namespace webignition\Tests\CssValidatorOutput\Parser\GetOutput;

use webignition\Tests\CssValidatorOutput\BaseTest;

class WarningRefPropertyTest extends BaseTest {

    /**
     * @var \webignition\CssValidatorOutput\CssValidatorOutput
     */
    private $cssValidatorOutput;
    
    public function setUp() {
        $this->cssValidatorOutput = $this->getParser(array(
            'rawOutput' => $this->getFixture('output09.txt')
        ))->getOutput();
    }

    public function testWarningsHaveNonBlankRefProperty() {
        foreach ($this->cssValidatorOutput->getWarnings() as $warning) {
            $this->assertTrue($warning->getRef() != '');
        }
    }
}