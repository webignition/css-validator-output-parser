<?php

use webignition\CssValidatorOutput\Parser;

class ParserRefDomainsToIgnoreIdnTest extends BaseTest { 
    
    private $rawOutput;
    
    private $expectedErrorValues = array(
        array(
            'ref' => 'http://artesan.xn--a-iga.com/style.css',
            'line' => 1,
            'context' => 'audio, canvas, video',
            'body' => 'one'
        ),       
        array(
            'ref' => 'http://artesan.ía.com/style.css',
            'line' => 2,
            'context' => 'html',
            'body' => 'two'
        ), 
        array(
            'ref' => 'http://three.example.com/style.css',
            'line' => 3,
            'context' => '.hide-text',
            'body' => 'three'
        )         
    );    
    
    public function setUp() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
        $this->rawOutput = $this->getFixture('output08.txt');
    }
    
    public function testRefDomainsToIgnoreDefault() {        
        $parser = new Parser();
        $parser->setRawOutput($this->rawOutput);        
        $parser->setIgnoreWarnings(true);
        
        $cssValidatorOutput = $parser->getOutput();
        $this->assertInstanceOf('webignition\CssValidatorOutput\CssValidatorOutput', $cssValidatorOutput);     
        
        $this->assertEquals(3, $cssValidatorOutput->getErrorCount());
        
        $errors = $cssValidatorOutput->getErrors();
        
        foreach ($errors as $errorIndex => $error) {
            /* @var $error \webignition\CssValidatorOutput\Message\Error */            
            $this->assertEquals($this->expectedErrorValues[$errorIndex]['ref'], $error->getRef());
            $this->assertEquals($this->expectedErrorValues[$errorIndex]['line'], $error->getLineNumber());
            $this->assertEquals($this->expectedErrorValues[$errorIndex]['context'], $error->getContext());
            $this->assertEquals($this->expectedErrorValues[$errorIndex]['body'], $error->getBody());               
        }
    }      
    
    public function testRefDomainsToIgnoreAsciiVariant() {        
        $parser = new Parser();
        $parser->setRawOutput($this->rawOutput);        
        $parser->setIgnoreWarnings(true);
        $parser->setRefDomainsToIgnore(array('artesan.xn--a-iga.com'));
        
        $cssValidatorOutput = $parser->getOutput();
        $this->assertInstanceOf('webignition\CssValidatorOutput\CssValidatorOutput', $cssValidatorOutput);     
        
        $this->assertEquals(1, $cssValidatorOutput->getErrorCount());
    }
    
    public function testRefDomainsToIgnoreUnicodeVariant() {        
        $parser = new Parser();
        $parser->setRawOutput($this->rawOutput);        
        $parser->setIgnoreWarnings(true);
        $parser->setRefDomainsToIgnore(array('artesan.ía.com'));
        
        $cssValidatorOutput = $parser->getOutput();
        $this->assertInstanceOf('webignition\CssValidatorOutput\CssValidatorOutput', $cssValidatorOutput);     
        
        $this->assertEquals(1, $cssValidatorOutput->getErrorCount());
    }
}