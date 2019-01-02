<?php

namespace webignition\Tests\CssValidatorOutput\Options;

use webignition\CssValidatorOutput\Options\Options;

class OptionsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider createDataProvider
     *
     * @param bool $vendorExtensionIssuesAsWarnings
     * @param string $outputFormat
     * @param string $language
     * @param int $warningLevel
     * @param string $medium
     * @param string $profile
     * @param string $expectedString
     */
    public function testCreate(
        $vendorExtensionIssuesAsWarnings,
        $outputFormat,
        $language,
        $warningLevel,
        $medium,
        $profile,
        $expectedString
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

    /**
     * @return array
     */
    public function createDataProvider()
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
