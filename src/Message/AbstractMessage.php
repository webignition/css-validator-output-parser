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
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $context
     */
    public function setContext($context)
    {
        $this->context = $context;
    }

    /**
     * @return string
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param int $lineNumber
     */
    public function setLineNumber($lineNumber)
    {
        $this->lineNumber = filter_var($lineNumber, FILTER_VALIDATE_INT, array('options' => array(
            'min_range' => 0,
            'default' => 0
        )));
    }

    /**
     * @return int
     */
    public function getLineNumber()
    {
        return $this->lineNumber;
    }

    /**
     * @param int $type
     */
    protected function setType($type)
    {
        $this->type = $type;
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
     * @param string $ref
     */
    public function setRef($ref)
    {
        $this->ref = $ref;
    }

    /**
     * @return string
     */
    public function getRef()
    {
        return $this->ref;
    }
}
