<?php

namespace webignition\CssValidatorOutput\Message;

class Error extends Message {

    public function __construct() {
        $this->setType(self::TYPE_ERROR);
    }

}
 