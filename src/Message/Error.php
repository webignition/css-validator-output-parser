<?php

namespace webignition\CssValidatorOutput\Message;

class Error extends AbstractMessage
{
    public function __construct()
    {
        $this->setType(self::TYPE_ERROR);
    }
}
