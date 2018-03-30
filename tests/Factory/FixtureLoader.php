<?php

namespace webignition\Tests\CssValidatorOutput\Factory;

class FixtureLoader
{
    public static function load($name)
    {
        $fixturePath = realpath(__DIR__ . '/../Fixtures/' . $name);

        if (empty($fixturePath)) {
            throw new \RuntimeException(sprintf(
                'Unknown fixture %s',
                $name
            ));
        }

        return file_get_contents($fixturePath);
    }
}
