<?php

namespace webignition\CssValidatorOutput\Parser\Tests;

class FixtureLoader
{
    public static function load(string $name): string
    {
        $fixturePath = realpath(__DIR__ . '/Fixtures/' . $name);

        if (empty($fixturePath)) {
            throw new \RuntimeException(sprintf(
                'Unknown fixture %s',
                $name
            ));
        }

        return file_get_contents($fixturePath);
    }
}
