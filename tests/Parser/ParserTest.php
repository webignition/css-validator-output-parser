<?php

namespace webignition\Tests\CssValidatorOutput\Parser;

use webignition\CssValidatorOutput\CssValidatorOutput;
use webignition\CssValidatorOutput\Options\Options;
use webignition\CssValidatorOutput\Parser\Configuration;
use webignition\CssValidatorOutput\Parser\Parser;
use webignition\Tests\CssValidatorOutput\Factory\FixtureLoader;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Parser
     */
    private $parser;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->parser = new Parser();
    }

    public function testSetConfigurationGetConfiguration()
    {
        $this->assertNull($this->parser->getConfiguration());

        $configuration = new Configuration();

        $this->parser->setConfiguration($configuration);
        $this->assertEquals($configuration, $this->parser->getConfiguration());
    }

    /**
     * @dataProvider getOutputSuccessfulOutputDataProvider
     *
     * @param array $configurationValues
     * @param string $rawOutput
     * @param int $expectedOutputErrorCount
     * @param int $expectedOutputWarningCount
     * @param array|null $expectedErrorValuesCollection
     */
    public function testGetOutputSuccessfulOutput(
        array $configurationValues,
        $rawOutput,
        $expectedOutputErrorCount,
        $expectedOutputWarningCount,
        $expectedErrorValuesCollection = null
    ) {
        $configuration = new Configuration($configurationValues);
        $configuration->setRawOutput($rawOutput);

        $this->parser->setConfiguration($configuration);

        $output = $this->parser->getOutput();

        $this->assertInstanceOf(CssValidatorOutput::class, $output);

        $this->assertEquals($expectedOutputErrorCount, $output->getErrorCount());
        $this->assertEquals($expectedOutputWarningCount, $output->getWarningCount());

        if (is_array($expectedErrorValuesCollection)) {
            $errors = $output->getErrors();

            foreach ($errors as $errorIndex => $error) {
                $expectedErrorValues = $expectedErrorValuesCollection[$errorIndex];

                $this->assertEquals($expectedErrorValues['ref'], $error->getRef());
                $this->assertEquals($expectedErrorValues['line'], $error->getLineNumber());
                $this->assertEquals($expectedErrorValues['context'], $error->getContext());
                $this->assertEquals($expectedErrorValues['message'], $error->getMessage());
            }
        }
    }

    /**
     * @return array
     */
    public function getOutputSuccessfulOutputDataProvider()
    {
        return [
            'no messages' => [
                'configurationValues' => [],
                'rawOutput' => FixtureLoader::load('ValidatorOutput/no-messages.txt'),
                'expectedOutputErrorCount' => 0,
                'expectedOutputWarningCount' => 0,
                'expectedErrors' => null,
            ],
            'string index out of bounds exception before regular output' => [
                'configurationValues' => [],
                'rawOutput' => FixtureLoader::load('ValidatorOutput/string-index-out-of-bounds-exception.txt'),
                'expectedOutputErrorCount' => 3,
                'expectedOutputWarningCount' => 0,
            ],
            'ignore false data url messages: false' => [
                'configurationValues' => [],
                'rawOutput' => FixtureLoader::load('ValidatorOutput/incorrect-data-url-background-image-errors.txt'),
                'expectedOutputErrorCount' => 1,
                'expectedOutputWarningCount' => 0,
            ],
            'ignore false data url messages: true' => [
                'configurationValues' => [
                    Configuration::KEY_IGNORE_FALSE_DATA_URL_MESSAGES => true,
                ],
                'rawOutput' => FixtureLoader::load('ValidatorOutput/incorrect-data-url-background-image-errors.txt'),
                'expectedOutputErrorCount' => 0,
                'expectedOutputWarningCount' => 0,
            ],
            'vextwarning=true; ignore vendor extension issues: false' => [
                'configurationValues' => [
                    Configuration::KEY_IGNORE_VENDOR_EXTENSION_ISSUES => false,
                ],
                'rawOutput' => FixtureLoader::load('ValidatorOutput/output06.txt'),
                'expectedOutputErrorCount' => 25,
                'expectedOutputWarningCount' => 95,
            ],
            'vextwarning=true; ignore vendor extension issues: true' => [
                'configurationValues' => [
                    Configuration::KEY_IGNORE_VENDOR_EXTENSION_ISSUES => true,
                ],
                'rawOutput' => FixtureLoader::load('ValidatorOutput/output06.txt'),
                'expectedOutputErrorCount' => 11,
                'expectedOutputWarningCount' => 5,
            ],
            'vextwarning=false; ignore vendor extension issues: false' => [
                'configurationValues' => [
                    Configuration::KEY_IGNORE_VENDOR_EXTENSION_ISSUES => false,
                ],
                'rawOutput' => FixtureLoader::load('ValidatorOutput/output07.txt'),
                'expectedOutputErrorCount' => 52,
                'expectedOutputWarningCount' => 5,
            ],
            'vextwarning=false; ignore vendor extension issues: true' => [
                'configurationValues' => [
                    Configuration::KEY_IGNORE_VENDOR_EXTENSION_ISSUES => true,
                ],
                'rawOutput' => FixtureLoader::load('ValidatorOutput/output07.txt'),
                'expectedOutputErrorCount' => 7,
                'expectedOutputWarningCount' => 5,
            ],
            'vendor-specific at rules; ignore vendor extension issues: false' => [
                'configurationValues' => [
                    Configuration::KEY_IGNORE_VENDOR_EXTENSION_ISSUES => false,
                ],
                'rawOutput' => FixtureLoader::load('ValidatorOutput/vendor-specific-at-rules.txt'),
                'expectedOutputErrorCount' => 11,
                'expectedOutputWarningCount' => 2,
            ],
            'vendor-specific at rules; ignore vendor extension issues: true' => [
                'configurationValues' => [
                    Configuration::KEY_IGNORE_VENDOR_EXTENSION_ISSUES => true,
                ],
                'rawOutput' => FixtureLoader::load('ValidatorOutput/vendor-specific-at-rules.txt'),
                'expectedOutputErrorCount' => 1,
                'expectedOutputWarningCount' => 0,
            ],
            'ignore warnings: false' => [
                'configurationValues' => [
                    Configuration::KEY_IGNORE_WARNINGS => false,
                ],
                'rawOutput' => FixtureLoader::load('ValidatorOutput/output01.txt'),
                'expectedOutputErrorCount' => 3,
                'expectedOutputWarningCount' => 2,
            ],
            'ignore warnings: true' => [
                'configurationValues' => [
                    Configuration::KEY_IGNORE_WARNINGS => true,
                ],
                'rawOutput' => FixtureLoader::load('ValidatorOutput/output01.txt'),
                'expectedOutputErrorCount' => 3,
                'expectedOutputWarningCount' => 0,
            ],
            'report vendor extension issues as warnings and ignore warnings' => [
                'configurationValues' => [
                    Configuration::KEY_REPORT_VENDOR_EXTENSION_ISSUES_AS_WARNINGS => true,
                    Configuration::KEY_IGNORE_WARNINGS => true,
                ],
                'rawOutput' => FixtureLoader::load('ValidatorOutput/vendor-specific-at-rules.txt'),
                'expectedOutputErrorCount' => 1,
                'expectedOutputWarningCount' => 0,
            ],
            'invalid PCDATA in validator output' => [
                'configurationValues' => [],
                'rawOutput' => FixtureLoader::load('ValidatorOutput/invalid-pcdata.txt'),
                'expectedOutputErrorCount' => 1,
                'expectedOutputWarningCount' => 0,
            ],
            'large output: 890 errors, 5 warnings' => [
                'configurationValues' => [],
                'rawOutput' => FixtureLoader::load('ValidatorOutput/output02.txt'),
                'expectedOutputErrorCount' => 890,
                'expectedOutputWarningCount' => 5,
            ],
            'large output: 623 errors, 272 warnings' => [
                'configurationValues' => [],
                'rawOutput' => FixtureLoader::load('ValidatorOutput/output03.txt'),
                'expectedOutputErrorCount' => 623,
                'expectedOutputWarningCount' => 272,
            ],
            'idn domains; none-ignored' => [
                'configurationValues' => [],
                'rawOutput' => FixtureLoader::load('ValidatorOutput/output08.txt'),
                'expectedOutputErrorCount' => 3,
                'expectedOutputWarningCount' => 0,
                'expectedErrorValuesCollection' => [
                    [
                        'ref' => 'http://artesan.xn--a-iga.com/style.css',
                        'line' => 1,
                        'context' => 'audio, canvas, video',
                        'message' => 'one'
                    ],
                    [
                        'ref' => 'http://artesan.ía.com/style.css',
                        'line' => 2,
                        'context' => 'html',
                        'message' => 'two'
                    ],
                    [
                        'ref' => 'http://three.example.com/style.css',
                        'line' => 3,
                        'context' => '.hide-text',
                        'message' => 'three'
                    ]
                ],
            ],
            'idn domains to ignore; ignore punycode variant' => [
                'configurationValues' => [
                    Configuration::KEY_REF_DOMAINS_TO_IGNORE => [
                        'artesan.xn--a-iga.com',
                    ],
                ],
                'rawOutput' => FixtureLoader::load('ValidatorOutput/output08.txt'),
                'expectedOutputErrorCount' => 1,
                'expectedOutputWarningCount' => 0,
                'expectedErrorValuesCollection' => [
                    [
                        'ref' => 'http://three.example.com/style.css',
                        'line' => 3,
                        'context' => '.hide-text',
                        'message' => 'three'
                    ]
                ],
            ],
            'idn domains to ignore; ignore utd8 variant' => [
                'configurationValues' => [
                    Configuration::KEY_REF_DOMAINS_TO_IGNORE => [
                        'artesan.ía.com',
                    ],
                ],
                'rawOutput' => FixtureLoader::load('ValidatorOutput/output08.txt'),
                'expectedOutputErrorCount' => 1,
                'expectedOutputWarningCount' => 0,
                'expectedErrorValuesCollection' => [
                    [
                        'ref' => 'http://three.example.com/style.css',
                        'line' => 3,
                        'context' => '.hide-text',
                        'message' => 'three'
                    ]
                ],
            ],
            'domains to ignore; ignore none' => [
                'configurationValues' => [
                    Configuration::KEY_REF_DOMAINS_TO_IGNORE => [],
                ],
                'rawOutput' => FixtureLoader::load('ValidatorOutput/output05.txt'),
                'expectedOutputErrorCount' => 6,
                'expectedOutputWarningCount' => 0,
                'expectedErrorValuesCollection' => [
                    [
                        'ref' => 'http://one.example.com/style.css',
                        'line' => 1,
                        'context' => 'audio, canvas, video',
                        'message' => 'one.1'
                    ],
                    [
                        'ref' => 'http://two.example.com/style.css',
                        'line' => 2,
                        'context' => 'html',
                        'message' => 'two.1'
                    ],
                    [
                        'ref' => 'http://three.example.com/style.css',
                        'line' => 3,
                        'context' => '.hide-text',
                        'message' => 'three.1'
                    ],
                    [
                        'ref' => 'http://one.example.com/style.css',
                        'line' => 4,
                        'context' => 'audio, canvas, video',
                        'message' => 'one.2'
                    ],
                    [
                        'ref' => 'http://two.example.com/style.css',
                        'line' => 5,
                        'context' => 'html',
                        'message' => 'two.2'
                    ],
                    [
                        'ref' => 'http://three.example.com/style.css',
                        'line' => 6,
                        'context' => '.hide-text',
                        'message' => 'three.2'
                    ],
                ],
            ],
            'domains to ignore; ignore one.example.com' => [
                'configurationValues' => [
                    Configuration::KEY_REF_DOMAINS_TO_IGNORE => [
                        'one.example.com',
                    ],
                ],
                'rawOutput' => FixtureLoader::load('ValidatorOutput/output05.txt'),
                'expectedOutputErrorCount' => 4,
                'expectedOutputWarningCount' => 0,
                'expectedErrorValuesCollection' => [
                    [
                        'ref' => 'http://two.example.com/style.css',
                        'line' => 2,
                        'context' => 'html',
                        'message' => 'two.1'
                    ],
                    [
                        'ref' => 'http://three.example.com/style.css',
                        'line' => 3,
                        'context' => '.hide-text',
                        'message' => 'three.1'
                    ],
                    [
                        'ref' => 'http://two.example.com/style.css',
                        'line' => 5,
                        'context' => 'html',
                        'message' => 'two.2'
                    ],
                    [
                        'ref' => 'http://three.example.com/style.css',
                        'line' => 6,
                        'context' => '.hide-text',
                        'message' => 'three.2'
                    ],
                ],
            ],
            'domains to ignore; ignore one.example.com,three.example.com' => [
                'configurationValues' => [
                    Configuration::KEY_REF_DOMAINS_TO_IGNORE => [
                        'one.example.com',
                        'three.example.com',
                    ],
                ],
                'rawOutput' => FixtureLoader::load('ValidatorOutput/output05.txt'),
                'expectedOutputErrorCount' => 2,
                'expectedOutputWarningCount' => 0,
                'expectedErrorValuesCollection' => [
                    [
                        'ref' => 'http://two.example.com/style.css',
                        'line' => 2,
                        'context' => 'html',
                        'message' => 'two.1'
                    ],
                    [
                        'ref' => 'http://two.example.com/style.css',
                        'line' => 5,
                        'context' => 'html',
                        'message' => 'two.2'
                    ],
                ],
            ],
            'report vendor extension issues as warnings' => [
                'configurationValues' => [
                    Configuration::KEY_REPORT_VENDOR_EXTENSION_ISSUES_AS_WARNINGS => true
                ],
                'rawOutput' => FixtureLoader::load('ValidatorOutput/vendor-specific-at-rules.txt'),
                'expectedOutputErrorCount' => 1,
                'expectedOutputWarningCount' => 12,
            ],
        ];
    }

    /**
     * @dataProvider getOutputExceptionOutputDataProvider
     *
     * @param string $rawOutput
     */
    public function testGetOutputExceptionOutput($rawOutput)
    {
        $configuration = new Configuration();
        $configuration->setRawOutput($rawOutput);

        $this->parser->setConfiguration($configuration);

        $output = $this->parser->getOutput();

        $this->assertInstanceOf(CssValidatorOutput::class, $output);
        $this->assertTrue($output->hasException());
        $this->assertEquals(0, $output->getWarningCount());
        $this->assertEquals(0, $output->getErrorCount());
    }

    /**
     * @return array
     */
    public function getOutputExceptionOutputDataProvider()
    {
        return [
            'HTTP 401' => [
                'rawOutput' => FixtureLoader::load('Exception/java.net.ProtocolException.http-401.txt'),
            ],
            'HTTP 404' => [
                'rawOutput' => FixtureLoader::load('Exception/java.io.FileNotFoundException.http-404.txt'),
            ],
            'HTTP 500' => [
                'rawOutput' => FixtureLoader::load('Exception/java.io.FileNotFoundException.http-500.txt'),
            ],
            'unknown mime type' => [
                'rawOutput' => FixtureLoader::load('Exception/java.io.IOException.unknownmimetype.txt'),
            ],
            'unknown file' => [
                'rawOutput' => FixtureLoader::load('Exception/java.lang.Exception.unknownfile.txt'),
            ],
            'illegal url' => [
                'rawOutput' => FixtureLoader::load(
                    'Exception/java.lang.IllegalArgumentException.illegalurl.txt'
                ),
            ],
            'unknown host' => [
                'rawOutput' => FixtureLoader::load('Exception/java.net.UnknownHostException.txt'),
            ],
            'ssl exception' => [
                'rawOutput' => FixtureLoader::load('Exception/javax.net.ssl.SSLException.txt'),
            ],
            'unknown exception' => [
                'rawOutput' => FixtureLoader::load('Exception/UnknownException.txt'),
            ],
        ];
    }

    public function testParseIncorrectUsageOutput()
    {
        $configuration = new Configuration();
        $configuration->setRawOutput(FixtureLoader::load('incorrect-usage.txt'));
        $this->parser->setConfiguration($configuration);

        $output = $this->parser->getOutput();

        $this->assertTrue($output->getIsIncorrectUsageOutput());

        $options = $output->getOptions();
        $this->assertNull($options);

        $datetime = $output->getDateTime();
        $this->assertNull($datetime);

        $this->assertEquals(0, $output->getMessageCount());
        $this->assertEquals(0, $output->getErrorCount());
        $this->assertEquals(0, $output->getWArningCount());
    }

    public function testParsesMetaData()
    {
        $configuration = new Configuration();
        $configuration->setRawOutput(FixtureLoader::load('ValidatorOutput/output01.txt'));
        $this->parser->setConfiguration($configuration);

        $output = $this->parser->getOutput();

        $options = $output->getOptions();
        $this->assertInstanceOf(Options::class, $options);

        $this->assertFalse($options->getVendorExtensionIssuesAsWarnings());
        $this->assertEquals('ucn', $options->getOutputFormat());
        $this->assertEquals('en', $options->getLanguage());
        $this->assertEquals(2, $options->getWarningLevel());
        $this->assertEquals('all', $options->getMedium());
        $this->assertEquals('css3', $options->getProfile());

        $datetime = $output->getDateTime();
        $this->assertInstanceOf(\DateTime::class, $datetime);

        $this->assertEquals('2012-12-27T04:09:39+00:00', $datetime->format('c'));
    }
}
