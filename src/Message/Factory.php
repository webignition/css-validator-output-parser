<?php

namespace webignition\CssValidatorOutput\Message;

class Factory
{
    public static function createFromDOMElement(\DOMElement $messageElement): ?AbstractMessage
    {
        $type = $messageElement->getAttribute('type');

        if (AbstractMessage::TYPE_ERROR !== $type && AbstractMessage::TYPE_WARNING !== $type) {
            return null;
        }

        $contextNode = $messageElement->getElementsByTagName('context')->item(0);

        return self::createFromArray([
            AbstractMessage::KEY_TYPE => $type,
            AbstractMessage::KEY_MESSAGE => trim($messageElement->getElementsByTagName('title')->item(0)->nodeValue),
            AbstractMessage::KEY_CONTEXT => $contextNode->nodeValue,
            AbstractMessage::KEY_REF => $messageElement->getAttribute('ref'),
            AbstractMessage::KEY_LINE_NUMBER => $contextNode->getAttribute('line'),
        ]);
    }

    public static function createWarningFromError(Error $error): Warning
    {
        $warning = self::createFromArray(array_merge($error->jsonSerialize(), [
            AbstractMessage::KEY_TYPE => AbstractMessage::TYPE_WARNING,
        ]));

        return $warning;
    }

    /**
     * @param array $messageData
     *
     * @return Error|Warning
     */
    public static function createFromArray(array $messageData)
    {
        $type = $messageData[AbstractMessage::KEY_TYPE];
        $message  = $messageData[AbstractMessage::KEY_MESSAGE];
        $context  = $messageData[AbstractMessage::KEY_CONTEXT];
        $ref  = $messageData[AbstractMessage::KEY_REF];
        $lineNumber  = $messageData[AbstractMessage::KEY_LINE_NUMBER];

        return AbstractMessage::TYPE_ERROR === $type
            ? new Error($message, $context, $ref, $lineNumber)
            : new Warning($message, $context, $ref, $lineNumber);
    }
}
