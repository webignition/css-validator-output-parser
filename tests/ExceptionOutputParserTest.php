<?php
/** @noinspection PhpDocSignatureInspection */

namespace webignition\CssValidatorOutput\Parser\Tests;

use webignition\CssValidatorOutput\Parser\ExceptionOutputParser;
use webignition\CssValidatorOutput\Model\ExceptionOutput;

class ExceptionOutputParserTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider isDataProvider
     */
    public function testIs(string $validatorBodyContent, bool $expectedIs)
    {
        $this->assertEquals($expectedIs, ExceptionOutputParser::is($validatorBodyContent));
    }

    public function isDataProvider(): array
    {
        return [
            'empty body content' => [
                'validatorBodyContent' => '',
                'expectedIs' => false,
            ],
            'regular validator output' => [
                'validatorBodyContent' => FixtureLoader::load('ValidatorOutput/example.txt'),
                'expectedIs' => false,
            ],
            'HTTP 401' => [
                'validatorBodyContent' => FixtureLoader::load('Exception/java.net.ProtocolException.http-401.txt'),
                'expectedIs' => true,
            ],
            'HTTP 404' => [
                'validatorBodyContent' => FixtureLoader::load('Exception/java.io.FileNotFoundException.http-404.txt'),
                'expectedIs' => true,
            ],
            'HTTP 500' => [
                'validatorBodyContent' => FixtureLoader::load('Exception/java.io.FileNotFoundException.http-500.txt'),
                'expectedIs' => true,
            ],
            'unknown mime type' => [
                'validatorBodyContent' => FixtureLoader::load('Exception/java.io.IOException.unknownmimetype.txt'),
                'expectedIs' => true,
            ],
            'unknown file' => [
                'validatorBodyContent' => FixtureLoader::load('Exception/java.lang.Exception.unknownfile.txt'),
                'expectedIs' => true,
            ],
            'illegal url' => [
                'validatorBodyContent' => FixtureLoader::load(
                    'Exception/java.lang.IllegalArgumentException.illegalurl.txt'
                ),
                'expectedIs' => true,
            ],
            'unknown host' => [
                'validatorBodyContent' => FixtureLoader::load('Exception/java.net.UnknownHostException.txt'),
                'expectedIs' => true,
            ],
            'ssl exception' => [
                'validatorBodyContent' => FixtureLoader::load('Exception/javax.net.ssl.SSLException.txt'),
                'expectedIs' => true,
            ],
            'unknown exception' => [
                'validatorBodyContent' => FixtureLoader::load('Exception/UnknownException.txt'),
                'expectedIs' => true,
            ],
        ];
    }

    /**
     * @dataProvider parseDataProvider
     */
    public function testParse(string $rawOutput, string $expectedType, string $expectedSubtype)
    {
        $headerBodyParts = explode("\n", $rawOutput, 2);
        $body = trim($headerBodyParts[1]);

        $output = ExceptionOutputParser::parse($body);

        $this->assertEquals($expectedType, $output->getType());
        $this->assertEquals($expectedSubtype, $output->getSubType());
    }

    public function parseDataProvider(): array
    {
        return [
            'regular validator output' => [
                'rawOutput' => FixtureLoader::load('ValidatorOutput/example.txt'),
                'expectedType' => ExceptionOutput::TYPE_UNKNOWN,
                'expectedSubtype' => '',
            ],
            'HTTP 401' => [
                'rawOutput' => FixtureLoader::load('Exception/java.net.ProtocolException.http-401.txt'),
                'expectedType' => ExceptionOutput::TYPE_HTTP,
                'expectedSubtype' => '401',
            ],
            'HTTP 404' => [
                'rawOutput' => FixtureLoader::load('Exception/java.io.FileNotFoundException.http-404.txt'),
                'expectedType' => ExceptionOutput::TYPE_HTTP,
                'expectedSubtype' => '404',
            ],
            'HTTP 500' => [
                'rawOutput' => FixtureLoader::load('Exception/java.io.FileNotFoundException.http-500.txt'),
                'expectedType' => ExceptionOutput::TYPE_HTTP,
                'expectedSubtype' => '500',
            ],
            'unknown mime type' => [
                'rawOutput' => FixtureLoader::load('Exception/java.io.IOException.unknownmimetype.txt'),
                'expectedType' => ExceptionOutput::TYPE_UNKNOWN_CONTENT_TYPE,
                'expectedSubtype' => 'application/pdf',
            ],
            'unknown file' => [
                'rawOutput' => FixtureLoader::load('Exception/java.lang.Exception.unknownfile.txt'),
                'expectedType' => ExceptionOutput::TYPE_UNKNOWN_FILE,
                'expectedSubtype' => '',
            ],
            'illegal url' => [
                'rawOutput' => FixtureLoader::load(
                    'Exception/java.lang.IllegalArgumentException.illegalurl.txt'
                ),
                'expectedType' => ExceptionOutput::TYPE_CURL,
                'expectedSubtype' => '3',
            ],
            'unknown host' => [
                'rawOutput' => FixtureLoader::load('Exception/java.net.UnknownHostException.txt'),
                'expectedType' => ExceptionOutput::TYPE_UNKNOWN_HOST,
                'expectedSubtype' => '',
            ],
            'ssl exception' => [
                'rawOutput' => FixtureLoader::load('Exception/javax.net.ssl.SSLException.txt'),
                'expectedType' => ExceptionOutput::TYPE_SSL_ERROR,
                'expectedSubtype' => '',
            ],
            'unknown exception' => [
                'rawOutput' => FixtureLoader::load('Exception/UnknownException.txt'),
                'expectedType' => ExceptionOutput::TYPE_UNKNOWN,
                'expectedSubtype' => '',
            ],
        ];
    }
}
