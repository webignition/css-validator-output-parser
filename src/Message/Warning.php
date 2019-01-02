<?php

namespace webignition\CssValidatorOutput\Message;

class Warning extends AbstractMessage
{
    const DEFAULT_LEVEL = 0;

    private $level = self::DEFAULT_LEVEL;

    public function __construct(
        string $message,
        string $context,
        string $ref,
        int $lineNumber,
        int $level = self::DEFAULT_LEVEL
    ) {
        parent::__construct($message, $context, $ref, $lineNumber, self::TYPE_WARNING);

        $this->level = filter_var($level, FILTER_VALIDATE_INT, ['options' => [
            'min_range' => 0,
            'default' => 0
        ]]);
    }

    public function getLevel(): int
    {
        return $this->level;
    }
}
