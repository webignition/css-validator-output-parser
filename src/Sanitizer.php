<?php

namespace webignition\CssValidatorOutput;

use Symfony\Polyfill\Mbstring\Mbstring;

/**
 * W3C CSS validator ucn output can contain invalid PCDATA as taken from source
 * CSS. This commonly includes unprintable characters.
 */
class Sanitizer
{
    /**
     * @var int[]
     */
    private $allowedIndividualCharacters = [
        9, // #x9
        10, // #xA
        13, // #xD
    ];

    /**
     * @var array
     */
    private $allowedCharacterRanges = [
        [32, 55295], // #x20-#xD7FF
        [57344, 65533], // #xE000-#xFFFD
        [65536, 1114111] // #x10000-#x10FFFF
    ];

    /**
     * @param string $rawOutput
     *
     * @return string
     */
    public function getSanitizedOutput($rawOutput)
    {
        $rawOutput = mb_convert_encoding($rawOutput, 'UTF-8');
        $sanitizedOutput = '';

        $rawOutputCharacters = preg_split('//u', $rawOutput, -1, PREG_SPLIT_NO_EMPTY);

        foreach ($rawOutputCharacters as $rawOutputCharacter) {
            if ($this->isCharacterInValidRange($rawOutputCharacter)) {
                $sanitizedOutput .= $rawOutputCharacter;
            } else {
                $sanitizedOutput .= '\x' . dechex(Mbstring::mb_ord($rawOutputCharacter));

            }
        }

        return $sanitizedOutput;
    }

    /**
     * @param string $character
     *
     * @return bool
     */
    private function isCharacterInValidRange($character)
    {
        $characterIndex = Mbstring::mb_ord($character);

        foreach ($this->allowedIndividualCharacters as $allowedInvidualCharacterIndex) {
            if ($characterIndex == $allowedInvidualCharacterIndex) {
                return true;
            }
        }

        foreach ($this->allowedCharacterRanges as $allowedCharacterRangeSet) {
            $minimum = $allowedCharacterRangeSet[0];
            $maximum = $allowedCharacterRangeSet[1];

            if ($characterIndex >= $minimum && $characterIndex <= $maximum) {
                return true;
            }
        }

        return false;
    }
}
