<?php

namespace webignition\Tests\CssValidatorOutput;
use webignition\CssValidatorOutput\Parser\Parser as CssValidatorOutputParser;
use webignition\CssValidatorOutput\Parser\Configuration;

abstract class BaseTest extends \PHPUnit_Framework_TestCase {

    const FIXTURES_BASE_PATH = '/fixtures/Common/';

    /**
     *
     * @var string
     */
    private $fixturePath = null;

    /**
     *
     * @param string $testClass
     * @param string $testMethod
     */
    protected function setTestFixturePath($testClass, $testMethod) {
        $this->fixturePath = __DIR__ . self::FIXTURES_BASE_PATH . $testMethod;
    }


    /**
     *
     * @return string
     */
    protected function getTestFixturePath() {
        return $this->fixturePath;
    }


    /**
     *
     * @param string $fixtureName
     * @return string
     */
    protected function getFixture($fixtureName) {
        if (file_exists($this->getTestFixturePath() . '/' . $fixtureName)) {
            return file_get_contents($this->getTestFixturePath() . '/' . $fixtureName);
        }

        return file_get_contents(__DIR__ . self::FIXTURES_BASE_PATH . $fixtureName);
    }


    /**
     *
     * @param array $properties
     * @return \webignition\CssValidatorOutput\Parser\Parser
     */
    protected function getParser($properties) {
        $configuration = new Configuration();
        $configuration->setRawOutput($properties['rawOutput']);

        if (isset($properties['configuration'])) {
            foreach ($properties['configuration'] as $key => $value) {
                $methodName = 'set' . $key;
                $configuration->$methodName($value);
            }
        }


        $parser = new CssValidatorOutputParser();
        $parser->setConfiguration($configuration);

        return $parser;
    }


    protected function assertTestYieldsGivenMessageErrorandWarningCount($properties) {
        $parser = $this->getParser($properties);
        $cssValidatorOutput = $parser->getOutput();

        if (!isset($properties['messageCount'])) {
            $properties['messageCount'] = $properties['errorCount'] + $properties['warningCount'];
        }

        $this->assertEquals($properties['messageCount'], $cssValidatorOutput->getMessageCount());
        $this->assertEquals($properties['errorCount'], $cssValidatorOutput->getErrorCount());
        $this->assertEquals($properties['warningCount'], $cssValidatorOutput->getWarningCount());
    }




}