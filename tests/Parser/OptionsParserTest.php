<?php

namespace webignition\Tests\CssValidatorOutput\Parser;

use webignition\CssValidatorOutput\Model\Options;
use webignition\CssValidatorOutput\Parser\OptionsParser;
use webignition\Tests\CssValidatorOutput\Factory\FixtureLoader;

class OptionsParserTest extends \PHPUnit\Framework\TestCase
{
    public function testParseValidOptions()
    {
        $optionsContent = FixtureLoader::load('Options/valid-options.txt');

        $optionsParser = new OptionsParser();
        $options = $optionsParser->parse($optionsContent);

        $this->assertInstanceOf(Options::class, $options);

        $this->assertFalse($options->getVendorExtensionIssuesAsWarnings());
        $this->assertEquals('ucn', $options->getOutputFormat());
        $this->assertEquals('en', $options->getLanguage());
        $this->assertEquals(2, $options->getWarningLevel());
        $this->assertEquals('all', $options->getMedium());
        $this->assertEquals('css3', $options->getProfile());
    }

    public function testParseInvalidOptions()
    {
        $optionsContent = FixtureLoader::load('Options/invalid-options.txt');

        $optionsParser = new OptionsParser();
        $options = $optionsParser->parse($optionsContent);

        $this->assertNull($options);
    }
}
