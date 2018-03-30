<?php

namespace webignition\CssValidatorOutput\Message;

abstract class AbstractMessage
{
    const TYPE_ERROR = 0;
    const TYPE_WARNING = 1;
    const TYPE_INFO = 2;

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
     * @var array
     */
    private $serializedTypes = [
        'error',
        'warning'
    ];

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
     * @return string
     */
    public function getSerializedType()
    {
        return $this->serializedTypes[$this->type];
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
}
