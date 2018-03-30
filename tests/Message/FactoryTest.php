<?php

namespace webignition\Tests\CssValidatorOutput\Message;

use webignition\CssValidatorOutput\Message\Error;
use webignition\CssValidatorOutput\Message\Factory;
use webignition\CssValidatorOutput\Message\Warning;
use webignition\Tests\CssValidatorOutput\Factory\FixtureLoader;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateErrorMessage()
    {
        $message = Factory::createFromDOMElement($this->createMessageDomElement('Partial/error-message.xml'));

        $this->assertInstanceOf(Error::class, $message);
        $this->assertEquals('Parse Error
            *display: inline;', $message->getMessage());
        $this->assertEquals('audio, canvas, video', $message->getContext());
        $this->assertEquals(28, $message->getLineNumber());
        $this->assertTrue($message->isError());
        $this->assertEquals('http://example.com/css/bootstrap.css', $message->getRef());
    }

    public function testParseWarningMessage()
    {
        $message = Factory::createFromDOMElement($this->createMessageDomElement('Partial/warning-message.xml'));

        $this->assertInstanceOf(Warning::class, $message);
        $this->assertEquals(
            "You should add a 'type' attribute with a value of 'text/css' to the 'link' element",
            $message->getMessage()
        );
        $this->assertEquals('', $message->getContext());
        $this->assertEquals(5, $message->getLineNumber());
        $this->assertTrue($message->isWarning());
        $this->assertEquals(0, $message->getLevel());

        $this->assertEquals('http://example.com/', $message->getRef());
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
