<?php

namespace webignition\CssValidatorOutput\ExceptionOutput;

use webignition\CssValidatorOutput\ExceptionOutput\Type\Type;
use webignition\CssValidatorOutput\ExceptionOutput\Type\Value;

class Parser
{
    /**
     * @var string
     */
    private $rawOutput = '';

    /**
     * @var string
     */
    private $rawOutputFirstLine = '';

    /**
     * @var ExceptionOutput
     */
    private $output;

    public static function is(string $validatorBodyContent): bool
    {
        if (substr_count($validatorBodyContent, '</observationresponse>')) {
            return false;
        }

        return preg_match('/java.*Exception:/', $validatorBodyContent) > 0;
    }

    public function setRawOutput(string $rawOutput)
    {
        $this->rawOutput = trim($rawOutput);
        $this->rawOutputFirstLine = substr($this->rawOutput, 0, strpos($this->rawOutput, "\n"));
        $this->output = null;
    }

    public function getOutput(): ExceptionOutput
    {
        if (is_null($this->output)) {
            $this->output = new ExceptionOutput();
            $this->parse();
        }

        return $this->output;
    }

    private function parse()
    {
        if ($this->isFileNotFoundError()) {
            $this->setType('http404');

            return;
        }

        if ($this->isHttpAuthProtocolExceptionOutput()) {
            $this->setType('http401');

            return;
        }

        if ($this->isIllegalUrlError()) {
            $this->setType('curl3');

            return;
        }

        if ($this->isInternalServerError()) {
            $this->setType('http500');

            return;
        }

        if ($this->isSslExceptionOutput()) {
            $this->setType(Value::SSL_EXCEPTION);

            return;
        }

        if ($this->isUnknownMimeTypeError()) {
            $this->setType(Value::UNKNOWN_MIME_TYPE);

            return;
        }

        if ($this->isUnknownHostError()) {
            $this->setType('curl6');

            return;
        }

        if ($this->isUnknownFileExceptionOutput()) {
            $this->setType(Value::UNKNOWN_FILE);

            return;
        }

        $this->setType(Value::UNKNOWN);

        return;
    }

    private function setType(string $type)
    {
        $this->output->setType(new Type($type));
    }

    private function isUnknownMimeTypeError(): bool
    {
        return preg_match('/Unknown mime type :/', $this->rawOutputFirstLine) > 0;
    }

    private function isInternalServerError(): bool
    {
        if (!$this->isFileNotFoundException()) {
            return false;
        }

        return preg_match('/Internal Server Error/', $this->rawOutputFirstLine) > 0;
    }

    private function isFileNotFoundError(): bool
    {
        if (!$this->isFileNotFoundException()) {
            return false;
        }

        return preg_match('/Not Found/', $this->rawOutputFirstLine) > 0;
    }

    private function isFileNotFoundException(): bool
    {
        return preg_match('/^java\.io\.FileNotFoundException:/', $this->rawOutputFirstLine) > 0;
    }

    private function isUnknownHostError(): bool
    {
        return preg_match('/^java\.net\.UnknownHostException:/', $this->rawOutputFirstLine) > 0;
    }

    private function isIllegalUrlError(): bool
    {
        return $this->rawOutputFirstLine == 'java.lang.IllegalArgumentException: protocol = http host = null';
    }

    private function isSslExceptionOutput(): bool
    {
        $signature = 'javax.net.ssl.SSLException';

        return substr($this->rawOutputFirstLine, 0, strlen($signature)) == $signature;
    }

    private function isHttpAuthProtocolExceptionOutput(): bool
    {
        return preg_match('/java\.net\.ProtocolException: (Basic|Digest)/', $this->rawOutputFirstLine) > 0;
    }

    private function isUnknownFileExceptionOutput(): bool
    {
        return preg_match('/java.lang.Exception: Unknown file/', $this->rawOutputFirstLine) > 0;
    }
}
