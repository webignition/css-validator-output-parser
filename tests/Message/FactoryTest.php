<?php

namespace webignition\Tests\CssValidatorOutput\Message;

use webignition\CssValidatorOutput\Message\AbstractMessage;
use webignition\CssValidatorOutput\Message\Error;
use webignition\CssValidatorOutput\Message\Factory;
use webignition\CssValidatorOutput\Message\Warning;
use webignition\Tests\CssValidatorOutput\Factory\FixtureLoader;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateErrorMessage()
    {
        $error = Factory::createFromDOMElement($this->createMessageDomElement('Partial/error-message.xml'));

        $this->assertInstanceOf(Error::class, $error);
        $this->assertEquals('Parse Error
            *display: inline;', $error->getMessage());
        $this->assertEquals('audio, canvas, video', $error->getContext());
        $this->assertEquals(28, $error->getLineNumber());
        $this->assertTrue($error->isError());
        $this->assertEquals('http://example.com/css/bootstrap.css', $error->getRef());

        $this->assertEquals(AbstractMessage::TYPE_ERROR, $error->getType());
        $this->assertEquals('error', $error->getSerializedType());
    }

    public function testParseWarningMessage()
    {
        $warning = Factory::createFromDOMElement($this->createMessageDomElement('Partial/warning-message.xml'));

        $this->assertInstanceOf(Warning::class, $warning);
        $this->assertEquals(
            "You should add a 'type' attribute with a value of 'text/css' to the 'link' element",
            $warning->getMessage()
        );
        $this->assertEquals('', $warning->getContext());
        $this->assertEquals(5, $warning->getLineNumber());
        $this->assertTrue($warning->isWarning());
        $this->assertEquals(Warning::DEFAULT_LEVEL, $warning->getLevel());
        $this->assertEquals('http://example.com/', $warning->getRef());

        $this->assertEquals(AbstractMessage::TYPE_WARNING, $warning->getType());
        $this->assertEquals('warning', $warning->getSerializedType());
    }

    public function testCreateFailure()
    {
        $domElement = new \DOMElement('foo');

        $message = Factory::createFromDOMElement($domElement);

        $this->assertNull($message);
    }

    public function testCreateWarningFromError()
    {
        $message = 'foo';
        $context = '.foo {}';
        $ref = 'http://example.com/foo.css';
        $lineNumber = 12;

        $error = new Error($message, $context, $ref, $lineNumber);

        $warning = Factory::createWarningFromError($error);

        $this->assertInstanceOf(Warning::class, $warning);
        $this->assertEquals($message, $warning->getMessage());
        $this->assertEquals($context, $warning->getContext());
        $this->assertEquals($ref, $warning->getRef());
        $this->assertEquals($lineNumber, $warning->getLineNumber());
        $this->assertEquals(Warning::DEFAULT_LEVEL, $warning->getLevel());
    }

    /**
     * @param string $fixtureName
     *
     * @return \DOMElement
     */
    private function createMessageDomElement($fixtureName)
    {
        $outputDom = new \DOMDocument();
        $outputDom->loadXML(FixtureLoader::load($fixtureName));

        return $outputDom->getElementsByTagName('message')->item(0);
    }
}
