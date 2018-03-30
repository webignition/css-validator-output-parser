<?php

namespace webignition\CssValidatorOutput\Message;

class Warning extends AbstractMessage
{
    /**
     * @var int
     */
    private $level = 0;

    public function __construct()
    {
        $this->setType(self::TYPE_WARNING);
    }

    /**
     * @param Error $error
     *
     * @return Warning
     */
    public static function fromError(Error $error)
    {
        $warning = new Warning();
        $warning->setContext($error->getContext());
        $warning->setLineNumber($error->getLineNumber());
        $warning->setMessage($error->getMessage());
        $warning->setRef($error->getRef());

        return $warning;
    }

    /**
     * @param int $level
     */
    public function setLevel($level)
    {
        $this->level = filter_var($level, FILTER_VALIDATE_INT, array('options' => array(
            'min_range' => 0,
            'default' => 0
        )));
    }

    /**
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }
}
