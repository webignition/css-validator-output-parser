<?php

namespace webignition\CssValidatorOutput;

/**
 * W3C CSS validator ucn output can contain invalid PCDATA as taken from source
 * CSS. This commonly includes unprintable characters.
 * 
 */
class Sanitizer {

    private $allowedIndividualCharacters = array(
        9, // #x9
        10, // #xA
        13, // #xD
    );    
    
    private $allowedCharacterRanges = array(
        array(32, 55295), // #x20-#xD7FF
        array(57344, 65533), // #xE000-#xFFFD
        array(65536, 1114111) // #x10000-#x10FFFF
    );
    
    private $replaceInvalidCharactersWithHexReference = true;
    
    
    /**
     * 
     * @param string $rawOutput
     * @return string
     */    
    public function getSanitizedOutput($rawOutput) {       
        $sanitizedOutput = '';
        
        $outputLength = strlen($rawOutput);
        for ($characterIndex = 0; $characterIndex < $outputLength; $characterIndex++) {            
            if ($this->isCharacterInValidRange($rawOutput[$characterIndex])) {
                $sanitizedOutput .= $rawOutput[$characterIndex];
            } else {
                if ($this->replaceInvalidCharactersWithHexReference) {
                    $sanitizedOutput .= '\x'.ord($rawOutput[$characterIndex]);
                }
            }
        }
        
        return $sanitizedOutput;
    }
    
    
    public function setReplaceInvalidCharactersWithHexReference($replaceInvalidCharactersWithHexReference) {
        $this->replaceInvalidCharactersWithHexReference = filter_var($replaceInvalidCharactersWithHexReference, FILTER_VALIDATE_BOOLEAN);
    }
    
    
    private function isCharacterInValidRange($character) {             
        $characterIndex = ord($character);
        
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