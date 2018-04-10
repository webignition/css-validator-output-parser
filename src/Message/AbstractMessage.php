<?php

namespace webignition\CssValidatorOutput\Message;

abstract class AbstractMessage implements \JsonSerializable
{
    const KEY_TYPE = 'type';
    const KEY_MESSAGE = 'message';
    const KEY_CONTEXT = 'context';
    const KEY_REF = 'ref';
    const KEY_LINE_NUMBER = 'line_number';

    const TYPE_ERROR = 'error';
    const TYPE_WARNING = 'warning';
    const TYPE_INFO = 'info';

    /**
     * @var string
     */
    private $message = '';

    /**
     * @var string
     */
    private $context = '';

    /**
     * @var string
     */
    private $ref = '';

    /**
     * @var int
     */
    private $lineNumber = 0;

    /**
     * @var int
     */
    private $type = self::TYPE_ERROR;

    /**
     * @param string $message
     * @param string $context
     * @param string $ref
     * @param int $lineNumber
     * @param int $type
     */
    public function __construct($message, $context, $ref, $lineNumber, $type)
    {
        $this->message = $message;
        $this->context = $context;
        $this->ref = $ref;
        $this->lineNumber = filter_var($lineNumber, FILTER_VALIDATE_INT, ['options' => [
            'min_range' => 0,
            'default' => 0
        ]]);
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @return int
     */
    public function getLineNumber()
    {
        return $this->lineNumber;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isError()
    {
        return self::TYPE_ERROR === $this->type;
    }

    /**
     * @return bool
     */
    public function isWarning()
    {
        return self::TYPE_WARNING === $this->type;
    }

    /**
     * @return string
     */
    public function getRef()
    {
        return $this->ref;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            self::KEY_TYPE => $this->type,
            self::KEY_MESSAGE => $this->message,
            self::KEY_CONTEXT => $this->context,
            self::KEY_REF => $this->ref,
            self::KEY_LINE_NUMBER => $this->lineNumber,
        ];
    }
}
