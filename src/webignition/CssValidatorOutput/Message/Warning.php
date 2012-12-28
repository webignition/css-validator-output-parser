<?php

namespace webignition\CssValidatorOutput\Message;

class Warning extends Message {

    
    /**
     *
     * @var int
     */
    private $level = 0;
    
    
    public function __construct() {
        $this->setType(self::TYPE_WARNING);
    }
    
    
    /**
     * 
     * @param int $level
     * @return \webignition\CssValidatorOutput\Message\Warning
     */
    public function setLevel($level) {
        $this->level = (int)$level;
        return $this;
    }
    
    
    /**
     * 
     * @return int
     */
    public function getLevel() {
        return $this->level;
    }
    
}
 