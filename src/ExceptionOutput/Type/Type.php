<?php

namespace webignition\CssValidatorOutput\ExceptionOutput\Type;

class Type
{
    /**
     * @var string
     */
    private $value = null;

    /**
     * @param string $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function get()
    {
        return $this->value;
    }
}
