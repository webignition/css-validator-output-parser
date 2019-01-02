<?php
/** @noinspection PhpDocSignatureInspection */

namespace webignition\Tests\CssValidatorOutput\Parser;

use webignition\CssValidatorOutput\Parser\Sanitizer;

class SanitizerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider getSanitizedOutputDataProvider
     */
    public function testGetSanitizedOutput(string $output, string $expectedSanitizedOutput)
    {
        $sanitizer = new Sanitizer();
        $sanitizedOutput = $sanitizer->getSanitizedOutput($output);

        $this->assertEquals($expectedSanitizedOutput, $sanitizedOutput);
    }

    public function getSanitizedOutputDataProvider(): array
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
