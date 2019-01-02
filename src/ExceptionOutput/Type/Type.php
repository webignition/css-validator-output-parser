<?php

namespace webignition\CssValidatorOutput\ExceptionOutput\Type;

class Type
{
    private $value = null;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function get(): string
    {
        return $this->value;
    }
}
