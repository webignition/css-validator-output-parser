<?php

namespace webignition\CssValidatorOutput\Parser;

use webignition\CssValidatorOutput\Model\Options;

class OptionsParser
{
    const VALID_PATTERN = '/{(([^=]+=[a-z0-9]+,\s?)+[^=]+=[a-z0-9]+)|([^=]+=[a-z0-9]+)}/';

    public function parse(string $optionsContent): ?Options
    {
        $isOptionsContentValid = preg_match(self::VALID_PATTERN, $optionsContent) > 0;
        if (!$isOptionsContentValid) {
            return null;
        }

        $optionsValuesString = substr($optionsContent, 1, strlen($optionsContent) - 2);
        $optionKeyValuePairs = explode(',', $optionsValuesString);

        $propertyValues = $this->getPropertyValuesFromKeyValuePairs($optionKeyValuePairs);

        return new Options(
            $propertyValues['vextwarning'],
            $propertyValues['output'],
            $propertyValues['lang'],
            $propertyValues['warning'],
            $propertyValues['medium'],
            $propertyValues['profile']
        );
    }

    private function getPropertyValuesFromKeyValuePairs(array $optionKeyValuePairs): array
    {
        $values = [];

        foreach ($optionKeyValuePairs as $optionKeyValuePair) {
            $keyValue = explode('=', $optionKeyValuePair);
            $key = trim($keyValue[0]);
            $value = trim($keyValue[1]);

            if ($value === 'true' || $value === 'false') {
                $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
            }

            if (ctype_digit($value)) {
                $value = (int)$value;
            }

            $values[$key] = $value;
        }

        return $values;
    }
}
