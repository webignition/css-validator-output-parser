<?php
namespace webignition\Tests\CssValidatorOutput\Parser\GetOutput;

use webignition\Tests\CssValidatorOutput\BaseTest;

class RefDomainsToIgnoreIdnTest extends BaseTest { 
    
    private $rawOutput;
    
    private $expectedErrorValues = array(
        array(
            'ref' => 'http://artesan.xn--a-iga.com/style.css',
            'line' => 1,
            'context' => 'audio, canvas, video',
            'message' => 'one'
        ),       
        array(
            'ref' => 'http://artesan.ía.com/style.css',
            'line' => 2,
            'context' => 'html',
            'message' => 'two'
        ), 
        array(
            'ref' => 'http://three.example.com/style.css',
            'line' => 3,
            'context' => '.hide-text',
            'message' => 'three'
        )         
    );    
    
    public function setUp() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
        $this->rawOutput = $this->getFixture('output08.txt');
    }
    
    public function testRefDomainsToIgnoreDefault() {        
        $parser = $this->getParser(array(
            'rawOutput' => $this->rawOutput,
            'configuration' => array(
                'ignoreWarnings' => true
            )
        ));
        
        $cssValidatorOutput = $parser->getOutput();
        
        $this->assertEquals(3, $cssValidatorOutput->getErrorCount());
        
        $errors = $cssValidatorOutput->getErrors();
        
        foreach ($errors as $errorIndex => $error) {
            /* @var $error \webignition\CssValidatorOutput\Message\Error */            
            $this->assertEquals($this->expectedErrorValues[$errorIndex]['ref'], $error->getRef());
            $this->assertEquals($this->expectedErrorValues[$errorIndex]['line'], $error->getLineNumber());
            $this->assertEquals($this->expectedErrorValues[$errorIndex]['context'], $error->getContext());
            $this->assertEquals($this->expectedErrorValues[$errorIndex]['message'], $error->getMessage());               
        }
    }      
    
    public function testRefDomainsToIgnoreAsciiVariant() {        
        $parser = $this->getParser(array(
            'rawOutput' => $this->rawOutput,
            'configuration' => array(
                'refDomainsToIgnore' => array(
                    'artesan.xn--a-iga.com'
                )
            )
        ));
        
        $cssValidatorOutput = $parser->getOutput();    
        
        $this->assertEquals(1, $cssValidatorOutput->getErrorCount());
    }
    
    public function testRefDomainsToIgnoreUnicodeVariant() {        
        $parser = $this->getParser(array(
            'rawOutput' => $this->rawOutput,
            'configuration' => array(
                'ignoreWarnings' => true,
                'refDomainsToIgnore' => array(
                    'artesan.ía.com'
                )
            )
        ));
        
        $cssValidatorOutput = $parser->getOutput();
        
        $this->assertEquals(1, $cssValidatorOutput->getErrorCount());
    }
}