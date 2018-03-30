<?php

namespace webignition\CssValidatorOutput\Message;

class Warning extends AbstractMessage
{
    const DEFAULT_LEVEL = 0;

    /**
     * @var int
     */
    private $level = self::DEFAULT_LEVEL;

    /**
     * @param string $message
     * @param string $context
     * @param string $ref
     * @param int $lineNumber
     * @param int $level
     */
    public function __construct($message, $context, $ref, $lineNumber, $level = self::DEFAULT_LEVEL)
    {
        parent::__construct($message, $context, $ref, $lineNumber, self::TYPE_WARNING);

        $this->level = filter_var($level, FILTER_VALIDATE_INT, ['options' => [
            'min_range' => 0,
            'default' => 0
        ]]);
    }

    /**
     * @param Error $error
     *
     * @return Warning
     */
    public static function fromError(Error $error)
    {
        return new Warning(
            $error->getMessage(),
            $error->getContext(),
            $error->getRef(),
            $error->getLineNumber()
        );
    }

    /**
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }
}
