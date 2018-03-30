<?php

namespace webignition\Tests\CssValidatorOutput;

use webignition\CssValidatorOutput\Sanitizer;

class SanitizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getSanitizedOutputDataProvider
     *
     * @param string $output
     * @param string $expectedSanitizedOutput
     */
    public function testGetSanitizedOutput($output, $expectedSanitizedOutput)
    {
        $sanitizer = new Sanitizer();
        $sanitizedOutput = $sanitizer->getSanitizedOutput($output);

        $this->assertEquals($expectedSanitizedOutput, $sanitizedOutput);
    }

    /**
     * @return array
     */
    public function getSanitizedOutputDataProvider()
    {
        return [
            'all valid, ascii' => [
                'output' => 'foo',
                'expectedSanitizedOutput' => 'foo',
            ],
            'unprintable, low, backspace' => [
                'output' => "\x8",
                'expectedSanitizedOutput' => '\x8',
            ],
            'allowed individual character: horizontal tab' => [
                'output' => "\x9",
                'expectedSanitizedOutput' => "\t",
            ],
            'allowed individual character: line return' => [
                'output' => "\xA",
                'expectedSanitizedOutput' => "\n",
            ],
            'allowed individual character: carriage return' => [
                'output' => "\xD",
                'expectedSanitizedOutput' => "\r",
            ],
        ];
    }
}
