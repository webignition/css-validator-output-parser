<?php
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocSignatureInspection */

namespace webignition\CssValidatorOutput\Parser\Tests;

use webignition\CssValidatorOutput\Model\ErrorMessage;
use webignition\CssValidatorOutput\Model\ExceptionOutput;
use webignition\CssValidatorOutput\Model\IncorrectUsageOutput;
use webignition\CssValidatorOutput\Model\Options;
use webignition\CssValidatorOutput\Model\OutputInterface;
use webignition\CssValidatorOutput\Model\ValidationOutput;
use webignition\CssValidatorOutput\Parser\Flags;
use webignition\CssValidatorOutput\Parser\InvalidValidatorOutputException;
use webignition\CssValidatorOutput\Parser\OutputParser;

class OutputParserTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var OutputParser
     */
    private $parser;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->parser = new OutputParser();
    }

    /**
     * @dataProvider getOutputSuccessfulOutputDataProvider
     */
    public function testParseSuccessfulOutput(
        int $flags,
        string $rawOutput,
        int $expectedOutputErrorCount,
        int $expectedOutputWarningCount,
        ?array $expectedErrorValuesCollection = null
    ) {
        /* @var ValidationOutput $output */
        $output = $this->parser->parse($rawOutput, $flags);

        $this->assertInstanceOf(OutputInterface::class, $output);
        $this->assertInstanceOf(ValidationOutput::class, $output);

        if ($output instanceof ValidationOutput) {
            $messageList = $output->getMessages();

            $this->assertEquals($expectedOutputErrorCount, $messageList->getErrorCount());
            $this->assertEquals($expectedOutputWarningCount, $messageList->getWarningCount());

            if (is_array($expectedErrorValuesCollection)) {
                /* @var ErrorMessage[] $errors */
                $errors = $messageList->getErrors();

                foreach ($errors as $errorIndex => $error) {
                    $expectedErrorValues = $expectedErrorValuesCollection[$errorIndex];

                    $this->assertEquals($expectedErrorValues['ref'], $error->getRef());
                    $this->assertEquals($expectedErrorValues['line'], $error->getLineNumber());
                    $this->assertEquals($expectedErrorValues['context'], $error->getContext());
                    $this->assertEquals($expectedErrorValues['message'], $error->getTitle());
                }
            }
        }
    }

    public function getOutputSuccessfulOutputDataProvider(): array
    {
        $allFlags =
            Flags::IGNORE_WARNINGS |
            Flags::IGNORE_VENDOR_EXTENSION_ISSUES |
            Flags::IGNORE_FALSE_IMAGE_DATA_URL_MESSAGES |
            Flags::REPORT_VENDOR_EXTENSION_ISSUES_AS_WARNINGS;

        return [
            'no messages' => [
                'flags' => Flags::NONE,
                'rawOutput' => FixtureLoader::load('ValidatorOutput/no-messages.txt'),
                'expectedOutputErrorCount' => 0,
                'expectedOutputWarningCount' => 0,
                'expectedErrors' => null,
            ],
            'string index out of bounds exception before regular output' => [
                'flags' => Flags::NONE,
                'rawOutput' => FixtureLoader::load('ValidatorOutput/string-index-out-of-bounds-exception.txt'),
                'expectedOutputErrorCount' => 3,
                'expectedOutputWarningCount' => 0,
            ],
            'false image data url messages; no flags' => [
                'flags' => Flags::NONE,
                'rawOutput' => FixtureLoader::load('ValidatorOutput/incorrect-data-url-background-image-errors.txt'),
                'expectedOutputErrorCount' => 4,
                'expectedOutputWarningCount' => 0,
            ],
            'false image data url messages; ignore false image data url messages flag' => [
                'flags' => Flags::IGNORE_FALSE_IMAGE_DATA_URL_MESSAGES,
                'rawOutput' => FixtureLoader::load('ValidatorOutput/incorrect-data-url-background-image-errors.txt'),
                'expectedOutputErrorCount' => 1,
                'expectedOutputWarningCount' => 0,
            ],
            'false image data url messages; all flags' => [
                'flags' => $allFlags,
                'rawOutput' => FixtureLoader::load('ValidatorOutput/incorrect-data-url-background-image-errors.txt'),
                'expectedOutputErrorCount' => 1,
                'expectedOutputWarningCount' => 0,
            ],
            'vextwarning=true; no flags' => [
                'flags' => Flags::NONE,
                'rawOutput' => FixtureLoader::load('ValidatorOutput/output06.txt'),
                'expectedOutputErrorCount' => 25,
                'expectedOutputWarningCount' => 95,
            ],
            'vextwarning=true; ignore vendor extension issues flag' => [
                'flags' => Flags::IGNORE_VENDOR_EXTENSION_ISSUES,
                'rawOutput' => FixtureLoader::load('ValidatorOutput/output06.txt'),
                'expectedOutputErrorCount' => 11,
                'expectedOutputWarningCount' => 5,
            ],
            'vextwarning=true; all flags' => [
                'flags' => $allFlags,
                'rawOutput' => FixtureLoader::load('ValidatorOutput/output06.txt'),
                'expectedOutputErrorCount' => 11,
                'expectedOutputWarningCount' => 0,
            ],
            'vextwarning=false; no flags' => [
                'flags' => Flags::NONE,
                'rawOutput' => FixtureLoader::load('ValidatorOutput/output07.txt'),
                'expectedOutputErrorCount' => 52,
                'expectedOutputWarningCount' => 5,
            ],
            'vextwarning=false; ignore vendor extension issues flag' => [
                'flags' => Flags::IGNORE_VENDOR_EXTENSION_ISSUES,
                'rawOutput' => FixtureLoader::load('ValidatorOutput/output07.txt'),
                'expectedOutputErrorCount' => 7,
                'expectedOutputWarningCount' => 5,
            ],
            'vendor-specific at rules; no flags' => [
                'flags' => Flags::NONE,
                'rawOutput' => FixtureLoader::load('ValidatorOutput/vendor-specific-at-rules.txt'),
                'expectedOutputErrorCount' => 11,
                'expectedOutputWarningCount' => 2,
            ],
            'vendor-specific at rules; ignore vendor extension issues flag' => [
                'flags' => Flags::IGNORE_VENDOR_EXTENSION_ISSUES,
                'rawOutput' => FixtureLoader::load('ValidatorOutput/vendor-specific-at-rules.txt'),
                'expectedOutputErrorCount' => 1,
                'expectedOutputWarningCount' => 0,
            ],
            'vendor-specific at rules; all flags' => [
                'flags' => $allFlags,
                'rawOutput' => FixtureLoader::load('ValidatorOutput/vendor-specific-at-rules.txt'),
                'expectedOutputErrorCount' => 1,
                'expectedOutputWarningCount' => 0,
            ],
            'ignore warnings: no flags' => [
                'flags' => Flags::NONE,
                'rawOutput' => FixtureLoader::load('ValidatorOutput/output01.txt'),
                'expectedOutputErrorCount' => 3,
                'expectedOutputWarningCount' => 2,
            ],
            'ignore warnings: ignore warnings flag' => [
                'flags' => Flags::IGNORE_WARNINGS,
                'rawOutput' => FixtureLoader::load('ValidatorOutput/output01.txt'),
                'expectedOutputErrorCount' => 3,
                'expectedOutputWarningCount' => 0,
            ],
            'ignore warnings: all flags' => [
                'flags' => $allFlags,
                'rawOutput' => FixtureLoader::load('ValidatorOutput/output01.txt'),
                'expectedOutputErrorCount' => 2,
                'expectedOutputWarningCount' => 0,
            ],
            'invalid PCDATA in validator output' => [
                'flags' => Flags::NONE,
                'rawOutput' => FixtureLoader::load('ValidatorOutput/invalid-pcdata.txt'),
                'expectedOutputErrorCount' => 1,
                'expectedOutputWarningCount' => 0,
            ],
            'large output: 890 errors, 5 warnings' => [
                'flags' => Flags::NONE,
                'rawOutput' => FixtureLoader::load('ValidatorOutput/output02.txt'),
                'expectedOutputErrorCount' => 890,
                'expectedOutputWarningCount' => 5,
            ],
            'large output: 623 errors, 272 warnings' => [
                'flags' => Flags::NONE,
                'rawOutput' => FixtureLoader::load('ValidatorOutput/output03.txt'),
                'expectedOutputErrorCount' => 623,
                'expectedOutputWarningCount' => 272,
            ],
            'vendor-specific at rules; report vendor extension issues as warnings flag' => [
                'flags' => Flags::REPORT_VENDOR_EXTENSION_ISSUES_AS_WARNINGS,
                'rawOutput' => FixtureLoader::load('ValidatorOutput/vendor-specific-at-rules.txt'),
                'expectedOutputErrorCount' => 1,
                'expectedOutputWarningCount' => 12,
            ],
            'invalid message, marked as neither error nor warning' => [
                'flags' => Flags::NONE,
                'rawOutput' => FixtureLoader::load('ValidatorOutput/invalid-message.txt'),
                'expectedOutputErrorCount' => 0,
                'expectedOutputWarningCount' => 0,
            ],
        ];
    }

    /**
     * @dataProvider getOutputExceptionOutputDataProvider
     */
    public function testParseExceptionOutput(string $rawOutput)
    {
        $output = $this->parser->parse($rawOutput);

        $this->assertInstanceOf(OutputInterface::class, $output);
        $this->assertInstanceOf(ExceptionOutput::class, $output);
    }

    public function getOutputExceptionOutputDataProvider(): array
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
        /* @var IncorrectUsageOutput $output */
        $output = $this->parser->parse(FixtureLoader::load('incorrect-usage.txt'));

        $this->assertInstanceOf(OutputInterface::class, $output);
        $this->assertInstanceOf(IncorrectUsageOutput::class, $output);
    }

    public function testParsesMetaData()
    {
        /* @var ValidationOutput $output */
        $output = $this->parser->parse(FixtureLoader::load('ValidatorOutput/output01.txt'));

        $this->assertInstanceOf(OutputInterface::class, $output);
        $this->assertInstanceOf(ValidationOutput::class, $output);

        if ($output instanceof ValidationOutput) {
            $options = $output->getOptions();
            $this->assertInstanceOf(Options::class, $options);

            $this->assertFalse($options->getVendorExtensionIssuesAsWarnings());
            $this->assertEquals('ucn', $options->getOutputFormat());
            $this->assertEquals('en', $options->getLanguage());
            $this->assertEquals(2, $options->getWarningLevel());
            $this->assertEquals('all', $options->getMedium());
            $this->assertEquals('css3', $options->getProfile());

            $observationResponse = $output->getObservationResponse();
            $datetime = $observationResponse->getDateTime();
            $this->assertInstanceOf(\DateTime::class, $datetime);
            $this->assertEquals('2012-12-27T04:09:39+00:00', $datetime->format('c'));
        }
    }

    public function testParseNonXmlBody()
    {
        $fixture = FixtureLoader::load('ValidatorOutput/non-xml-body.txt');

        try {
            $this->parser->parse($fixture);
            $this->fail(InvalidValidatorOutputException::class . ' not thrown');
        } catch (InvalidValidatorOutputException $invalidValidatorOutputException) {
            $this->assertEquals(trim($fixture), $invalidValidatorOutputException->getRawOutput());
        }
    }
}
