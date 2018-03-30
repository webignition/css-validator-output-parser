<?php

namespace webignition\CssValidatorOutput\Message;

class Factory
{
    const TYPE_ERROR = 'error';
    const TYPE_WARNING = 'warning';

    /**
     * @param \DOMElement $messageElement
     *
     * @return null|Error|Warning
     */
    public static function createFromDOMElement(\DOMElement $messageElement)
    {
        $type = $messageElement->getAttribute('type');

        if (self::TYPE_ERROR !== $type && self::TYPE_WARNING !== $type) {
            return null;
        }

        $contextNode = $messageElement->getElementsByTagName('context')->item(0);

        $ref = $messageElement->getAttribute('ref');
        $context = $contextNode->nodeValue;
        $lineNumber = $contextNode->getAttribute('line');
        $message = trim($messageElement->getElementsByTagName('title')->item(0)->nodeValue);

        return self::TYPE_ERROR === $type
            ? new Error($message, $context, $ref, $lineNumber)
            : new Warning($message, $context, $ref, $lineNumber);
    }

    /**
     * @param Error $error
     *
     * @return Warning
     */
    public static function createWarningFromError(Error $error)
    {
        return new Warning(
            $error->getMessage(),
            $error->getContext(),
            $error->getRef(),
            $error->getLineNumber()
        );
    }
}
