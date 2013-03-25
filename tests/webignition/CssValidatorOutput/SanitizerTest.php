<?php

use webignition\CssValidatorOutput\Sanitizer;

class SanitizerTest extends BaseTest {
    
    public function testReplaceInvalidCharactersWithHexReference() {        
        $sanitizer = new Sanitizer();        
        $this->assertEquals('\x1', $sanitizer->getSanitizedOutput("\x01"));     
    } 
}