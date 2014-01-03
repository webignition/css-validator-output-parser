<?php

namespace webignition\Tests\CssValidatorOutput\Options;

use webignition\Tests\CssValidatorOutput\BaseTest;

class ToStringTest extends BaseTest {
    
    public function testDefaultValues() {
        $options = new \webignition\CssValidatorOutput\Options\Options();
        $this->assertEquals('{vextwarning=false, output=text, lang=en, warning=2, medium=all, profile=css3}', (string)$options);
    }
    
    
    public function testWithAllPropertiesChanged() {
        $options = new \webignition\CssValidatorOutput\Options\Options();
        $options->setLanguage('fr');
        $options->setMedium('screen');  
        $options->setOutputFormat('ucn');
        $options->setProfile('css1');
        $options->setVendorExtensionIssuesAsWarnings(true);
        $options->setWarningLevel(1);
        
        $this->assertEquals('{vextwarning=true, output=ucn, lang=fr, warning=1, medium=screen, profile=css1}', (string)$options);
    }    
}