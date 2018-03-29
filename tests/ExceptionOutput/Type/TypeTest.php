<?php

namespace webignition\Tests\CssValidatorOutput\ExceptionOutput\Type;

use webignition\CssValidatorOutput\ExceptionOutput\Type\Type;

class TypeTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $type = new Type(1);

        $this->assertEquals(1, $type->get());
    }
}
