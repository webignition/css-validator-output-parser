<?php

namespace webignition\CssValidatorOutput\ExceptionOutput;

use webignition\CssValidatorOutput\ExceptionOutput\Type\Type;

class ExceptionOutput
{
    /**
     * @var Type
     */
    private $type = null;

    public function setType(Type $type)
    {
        $this->type = $type;
    }

    /**
     * @param string $name
     * @param array $arguments Not Used
     *
     * @return bool
     */
    public function __call($name, $arguments)
    {
        return $this->type->get() == str_replace('is', '', strtolower($name));
    }

    public function getType(): Type
    {
        return $this->type;
    }

    public function isHttpError(): bool
    {
        return substr($this->type->get(), 0, strlen('http')) === 'http';
    }

    public function isHttpClientError(): bool
    {
        return substr($this->type->get(), 0, strlen('http4')) === 'http4';
    }

    public function isHttpServerError(): bool
    {
        return substr($this->type->get(), 0, strlen('http5')) === 'http5';
    }

    public function getHttpStatusCode(): ?int
    {
        if (!$this->isHttpError()) {
            return null;
        }

        return (int)str_replace('http', '', $this->type->get());
    }

    public function isCurlError(): bool
    {
        return substr($this->type->get(), 0, strlen('curl')) === 'curl';
    }

    public function getCurlCode(): ?int
    {
        if (!$this->isCurlError()) {
            return null;
        }

        return (int)str_replace('curl', '', $this->type->get());
    }
}
