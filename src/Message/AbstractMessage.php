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

    private $message = '';
    private $context = '';
    private $ref = '';
    private $lineNumber = 0;
    private $type = self::TYPE_ERROR;

    public function __construct(string $message, string $context, string $ref, int $lineNumber, string $type)
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

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getContext(): string
    {
        return $this->context;
    }

    public function getLineNumber(): int
    {
        return $this->lineNumber;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function isError(): bool
    {
        return self::TYPE_ERROR === $this->type;
    }

    public function isWarning(): bool
    {
        return self::TYPE_WARNING === $this->type;
    }

    public function getRef(): string
    {
        return $this->ref;
    }

    public function jsonSerialize(): array
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
