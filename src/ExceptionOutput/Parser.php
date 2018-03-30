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

    /**
     * @param string $validatorBodyContent
     *
     * @return bool
     */
    public static function is($validatorBodyContent)
    {
        if (substr_count($validatorBodyContent, '</observationresponse>')) {
            return false;
        }

        return preg_match('/java.*Exception:/', $validatorBodyContent) > 0;
    }

    /**
     * @param string $rawOutput
     */
    public function setRawOutput($rawOutput)
    {
        $this->rawOutput = trim($rawOutput);
        $this->rawOutputFirstLine = substr($this->rawOutput, 0, strpos($this->rawOutput, "\n"));
        $this->output = null;
    }

    /**
     *
     * @return ExceptionOutput
     */
    public function getOutput()
    {
        if (is_null($this->output)) {
            $this->output = new ExceptionOutput();
            $this->parse();
        }

        return $this->output;
    }

    /**
     *
     * @return ExceptionOutput
     */
    private function parse()
    {
        if ($this->isFileNotFoundError()) {
            return $this->setType('http404');
        }

        if ($this->isHttpAuthProtocolExceptionOutput()) {
            return $this->setType('http401');
        }

        if ($this->isIllegalUrlError()) {
            return $this->setType('curl3');
        }

        if ($this->isInternalServerError()) {
            return $this->setType('http500');
        }

        if ($this->isSslExceptionOutput()) {
            return $this->setType(Value::SSL_EXCEPTION);
        }

        if ($this->isUnknownMimeTypeError()) {
            return $this->setType(Value::UNKNOWN_MIME_TYPE);
        }

        if ($this->isUnknownHostError()) {
            return $this->setType('curl6');
        }

        if ($this->isUnknownFileExceptionOutput()) {
            return $this->setType(Value::UNKNOWN_FILE);
        }

        return $this->setType(Value::UNKNOWN);
    }

    /**
     * @param string $type
     *
     * @return ExceptionOutput
     */
    private function setType($type)
    {
        $this->output->setType(new Type($type));

        return $this->output;
    }

    /**
     * @return bool
     */
    private function isUnknownMimeTypeError()
    {
        return preg_match('/Unknown mime type :/', $this->rawOutputFirstLine) > 0;
    }

    /**
     * @return bool
     */
    private function isInternalServerError()
    {
        if (!$this->isFileNotFoundException()) {
            return false;
        }

        return preg_match('/Internal Server Error/', $this->rawOutputFirstLine) > 0;
    }

    /**
     * @return bool
     */
    private function isFileNotFoundError()
    {
        if (!$this->isFileNotFoundException()) {
            return false;
        }

        return preg_match('/Not Found/', $this->rawOutputFirstLine) > 0;
    }

    /**
     * @return bool
     */
    private function isFileNotFoundException()
    {
        return preg_match('/^java\.io\.FileNotFoundException:/', $this->rawOutputFirstLine) > 0;
    }

    /**
     * @return bool
     */
    private function isUnknownHostError()
    {
        return preg_match('/^java\.net\.UnknownHostException:/', $this->rawOutputFirstLine) > 0;
    }

    /**
     * @return bool
     */
    private function isIllegalUrlError()
    {
        return $this->rawOutputFirstLine == 'java.lang.IllegalArgumentException: protocol = http host = null';
    }

    /**
     * @return bool
     */
    private function isSslExceptionOutput()
    {
        $signature = 'javax.net.ssl.SSLException';

        return substr($this->rawOutputFirstLine, 0, strlen($signature)) == $signature;
    }

    /**
     * @return bool
     */
    private function isHttpAuthProtocolExceptionOutput()
    {
        return preg_match('/java\.net\.ProtocolException: (Basic|Digest)/', $this->rawOutputFirstLine) > 0;
    }

    /**
     * @return bool
     */
    private function isUnknownFileExceptionOutput()
    {
        return preg_match('/java.lang.Exception: Unknown file/', $this->rawOutputFirstLine) > 0;
    }
}
