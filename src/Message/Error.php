<?php

namespace webignition\CssValidatorOutput\Message;

class Error extends AbstractMessage
{
    public function __construct(string $message, string $context, string $ref, int $lineNumber)
    {
        parent::__construct($message, $context, $ref, $lineNumber, self::TYPE_ERROR);
    }
}
