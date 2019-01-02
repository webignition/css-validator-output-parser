<?php

namespace webignition\Tests\CssValidatorOutput;

use webignition\CssValidatorOutput\CssValidatorOutput;
use webignition\CssValidatorOutput\Message\Error;
use webignition\CssValidatorOutput\Message\Warning;

class CssValidatorOutputTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var CssValidatorOutput
     */
    private $output;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->output = new CssValidatorOutput();
    }

    public function testGetSetSourceUrl()
    {
        $sourceUrl = 'http://example.com/foo.css';
        $this->assertEquals('', $this->output->getSourceUrl());

        $this->output->setSourceUrl($sourceUrl);
        $this->assertEquals($sourceUrl, $this->output->getSourceUrl());
    }

    public function testGetMessagesGetErrorsGetWarnings()
    {
        $error = new Error('error message', '.foo', 'http://example.com/foo.css', 12);
        $warning = new Warning('warning message', '.bar', 'http://example.com/foo.css', 9);

        $this->output->addMessage($error);
        $this->output->addMessage($warning);

        $this->assertEquals([$error, $warning], $this->output->getMessages());
        $this->assertEquals([$error], $this->output->getErrors());
        $this->assertEquals([$warning], $this->output->getWarnings());
    }

    public function testGetErrorsByUrl()
    {
        $error1 = new Error('error message', '.foo', 'http://example.com/one.css', 12);
        $error2 = new Error('error message', '.foo', 'http://example.com/two.css', 12);
        $error3 = new Error('error message', '.foo', 'http://example.com/two.css', 12);

        $warning1 = new Warning('warning message', '.bar', 'http://example.com/one.css', 9);
        $warning2 = new Warning('warning message', '.bar', 'http://example.com/two.css', 9);

        $this->output->addMessage($error1);
        $this->output->addMessage($error2);
        $this->output->addMessage($error3);
        $this->output->addMessage($warning1);
        $this->output->addMessage($warning2);

        $this->assertEquals([$error1], $this->output->getErrorsByUrl('http://example.com/one.css'));
        $this->assertEquals([$error2, $error3], $this->output->getErrorsByUrl('http://example.com/two.css'));
    }
}
