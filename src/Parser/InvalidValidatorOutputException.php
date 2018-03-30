<?php

namespace webignition\CssValidatorOutput\Parser;

class InvalidValidatorOutputException extends \Exception
{
    const MESSAGE = 'Invalid validator output';
    const CODE = 1;

    /**
     * @var string
     */
    private $rawOutput;

    public function __construct($rawOutput)
    {
        parent::__construct(self::MESSAGE, self::CODE);

        $this->rawOutput = $rawOutput;
    }

    /**
     * @return string
     */
    public function getRawOutput()
    {
        return $this->rawOutput;
    }
}
