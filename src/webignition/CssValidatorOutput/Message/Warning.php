<?php

namespace webignition\CssValidatorOutput\Message;

class Warning extends Message {

    
    /**
     *
     * @var int
     */
    private $level = 0;
    
    
    /**
     * 
     * @param \webignition\CssValidatorOutput\Message\Error $error
     * @return \webignition\CssValidatorOutput\Message\Warning
     */
    public static function fromError(Error $error) {
        $warning = new Warning();
        $warning->setContext($error->getContext());
        $warning->setLineNumber($error->getLineNumber());
        $warning->setMessage($error->getMessage());
        $warning->setRef($error->getRef());
        
        return $warning;
    }
    
    
    public function __construct() {
        $this->setType(self::TYPE_WARNING);
    }
    
    
    /**
     * 
     * @param int $level
     * @return \webignition\CssValidatorOutput\Message\Warning
     */
    public function setLevel($level) {
        $this->level = filter_var($level, FILTER_VALIDATE_INT, array('options' => array(
            'min_range' => 0,
            'default' => 0
        )));

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
 