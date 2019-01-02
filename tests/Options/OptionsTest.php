<?php
/** @noinspection PhpDocSignatureInspection */

namespace webignition\Tests\CssValidatorOutput\Options;

use webignition\CssValidatorOutput\Options\Options;

class OptionsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(
        bool $vendorExtensionIssuesAsWarnings,
        string $outputFormat,
        string $language,
        int $warningLevel,
        string $medium,
        string $profile,
        string $expectedString
    ) {
        $options = new Options(
            $vendorExtensionIssuesAsWarnings,
            $outputFormat,
            $language,
            $warningLevel,
            $medium,
            $profile
        );

        $this->assertEquals($expectedString, (string)$options);
    }

    public function createDataProvider(): array
    {
        return [
            'default' => [
                'vendorExtensionIssuesAsWarnings' => true,
                'outputFormat' => 'ucn',
                'language' => 'en',
                'warningLevel' => 2,
                'medium' => 'all',
                'profile' => 'css3',
                'expectedString' => '{vextwarning=true, output=ucn, lang=en, warning=2, medium=all, profile=css3}',
            ],
        ];
    }
}
