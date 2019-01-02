<?php

namespace webignition\Tests\CssValidatorOutput\Options;

use webignition\CssValidatorOutput\Options\Options;
use webignition\CssValidatorOutput\Options\Parser as CssValidatorOutputOptionsParser;
use webignition\Tests\CssValidatorOutput\Factory\FixtureLoader;

class ParserTest extends \PHPUnit\Framework\TestCase
{
    public function testParseValidOptions()
    {
        $optionsContent = FixtureLoader::load('Options/valid-options.txt');

        $optionsParser = new CssValidatorOutputOptionsParser();
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

        $optionsParser = new CssValidatorOutputOptionsParser();
        $options = $optionsParser->parse($optionsContent);

        $this->assertNull($options);
    }
}
