<?php

namespace webignition\CssValidatorOutput\Parser;

class InvalidValidatorOutputException extends \Exception
{
    const MESSAGE = 'Invalid validator output';
    const CODE = 1;

    private $rawOutput;

    public function __construct(string $rawOutput)
    {
        parent::__construct(self::MESSAGE, self::CODE);

        $this->rawOutput = $rawOutput;
    }

    public function getRawOutput(): string
    {
        return $this->rawOutput;
    }
}
