<?php

namespace webignition\Tests\CssValidatorOutput\ExceptionOutput;

use webignition\CssValidatorOutput\ExceptionOutput\Parser;
use webignition\CssValidatorOutput\ExceptionOutput\Type\Value;
use webignition\Tests\CssValidatorOutput\Factory\FixtureLoader;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider isDataProvider
     *
     * @param string $validatorBodyContent
     * @param bool $expectedIs
     */
    public function testIs($validatorBodyContent, $expectedIs)
    {
        $this->assertEquals($expectedIs, Parser::is($validatorBodyContent));
    }

    /**
     * @return array
     */
    public function isDataProvider()
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
     * @dataProvider getOutputDataProvider
     *
     * @param string $rawOutput
     * @param string $expectedOutputType
     */
    public function testGetOutput($rawOutput, $expectedOutputType)
    {
        $headerBodyParts = explode("\n", $rawOutput, 2);
        $body = trim($headerBodyParts[1]);

        $parser = new Parser();
        $parser->setRawOutput($body);

        $output = $parser->getOutput();

        $this->assertEquals($expectedOutputType, $output->getType()->get());
    }

    public function getOutputDataProvider()
    {
        return [
            'regular validator output' => [
                'validatorBodyContent' => FixtureLoader::load('ValidatorOutput/example.txt'),
                'expectedOutputType' => Value::UNKNOWN,
            ],
            'HTTP 401' => [
                'validatorBodyContent' => FixtureLoader::load('Exception/java.net.ProtocolException.http-401.txt'),
                'expectedOutputType' => 'http401',
            ],
            'HTTP 404' => [
                'validatorBodyContent' => FixtureLoader::load('Exception/java.io.FileNotFoundException.http-404.txt'),
                'expectedOutputType' => 'http404',
            ],
            'HTTP 500' => [
                'validatorBodyContent' => FixtureLoader::load('Exception/java.io.FileNotFoundException.http-500.txt'),
                'expectedOutputType' => 'http500',
            ],
            'unknown mime type' => [
                'validatorBodyContent' => FixtureLoader::load('Exception/java.io.IOException.unknownmimetype.txt'),
                'expectedOutputType' => Value::UNKNOWN_MIME_TYPE,
            ],
            'unknown file' => [
                'validatorBodyContent' => FixtureLoader::load('Exception/java.lang.Exception.unknownfile.txt'),
                'expectedOutputType' => Value::UNKNOWN_FILE,
            ],
            'illegal url' => [
                'validatorBodyContent' => FixtureLoader::load(
                    'Exception/java.lang.IllegalArgumentException.illegalurl.txt'
                ),
                'expectedOutputType' => 'curl3',
            ],
            'unknown host' => [
                'validatorBodyContent' => FixtureLoader::load('Exception/java.net.UnknownHostException.txt'),
                'expectedOutputType' => 'curl6',
            ],
            'ssl exception' => [
                'validatorBodyContent' => FixtureLoader::load('Exception/javax.net.ssl.SSLException.txt'),
                'expectedOutputType' => Value::SSL_EXCEPTION,
            ],
            'unknown exception' => [
                'validatorBodyContent' => FixtureLoader::load('Exception/UnknownException.txt'),
                'expectedOutputType' => Value::UNKNOWN,
            ],
        ];
    }
}
