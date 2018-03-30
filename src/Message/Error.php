<?php

namespace webignition\CssValidatorOutput\Message;

class Error extends AbstractMessage
{
    /**
     * @param string $message
     * @param string $context
     * @param string $ref
     * @param int $lineNumber
     */
    public function __construct($message, $context, $ref, $lineNumber)
    {
        parent::__construct($message, $context, $ref, $lineNumber, self::TYPE_ERROR);
    }
}
