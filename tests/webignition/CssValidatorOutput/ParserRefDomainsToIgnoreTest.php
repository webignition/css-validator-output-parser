<?php

use webignition\CssValidatorOutput\Parser;
use webignition\NormalisedUrl\NormalisedUrl;

class ParserRefDomainsToIgnoreTest extends BaseTest {
    
    private $rawOutput;
    
    private $expectedErrorValues = array(
        array(
            'ref' => 'http://one.example.com/style.css',
            'line' => 1,
            'context' => 'audio, canvas, video',
            'body' => 'one.1'
        ),       
        array(
            'ref' => 'http://two.example.com/style.css',
            'line' => 2,
            'context' => 'html',
            'body' => 'two.1'
        ), 
        array(
            'ref' => 'http://three.example.com/style.css',
            'line' => 3,
            'context' => '.hide-text',
            'body' => 'three.1'
        ), 
        array(
            'ref' => 'http://one.example.com/style.css',
            'line' => 4,
            'context' => 'audio, canvas, video',
            'body' => 'one.2'
        ), 
        array(
            'ref' => 'http://two.example.com/style.css',
            'line' => 5,
            'context' => 'html',
            'body' => 'two.2'
        ), 
        array(
            'ref' => 'http://three.example.com/style.css',
            'line' => 6,
            'context' => '.hide-text',
            'body' => 'three.2'
        ),         
    );    
    
    public function setUp() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
        $this->rawOutput = $this->getFixture('output05.txt');
    }
    
    public function testRefDomainsToIgnoreDefault() {        
        $parser = new Parser();
        $parser->setRawOutput($this->rawOutput);        
        $parser->setIgnoreWarnings(true);
        
        $cssValidatorOutput = $parser->getOutput();
        $this->assertInstanceOf('webignition\CssValidatorOutput\CssValidatorOutput', $cssValidatorOutput);     
        
        $this->assertEquals(6, $cssValidatorOutput->getErrorCount());
        
        $errors = $cssValidatorOutput->getErrors();
        
        foreach ($errors as $errorIndex => $error) {
            /* @var $error \webignition\CssValidatorOutput\Message\Error */            
            $this->assertEquals($this->expectedErrorValues[$errorIndex]['ref'], $error->getRef());
            $this->assertEquals($this->expectedErrorValues[$errorIndex]['line'], $error->getLineNumber());
            $this->assertEquals($this->expectedErrorValues[$errorIndex]['context'], $error->getContext());
            $this->assertEquals($this->expectedErrorValues[$errorIndex]['body'], $error->getBody());               
        }
    }      
    
    public function testRefDomainsToIgnoreIgnoreOne() {        
        $parser = new Parser();
        $parser->setRawOutput($this->rawOutput);        
        $parser->setIgnoreWarnings(true);
        $parser->setRefDomainsToIgnore(array('one.example.com'));
        
        $cssValidatorOutput = $parser->getOutput();
        $this->assertInstanceOf('webignition\CssValidatorOutput\CssValidatorOutput', $cssValidatorOutput);     
        
        $this->assertEquals(4, $cssValidatorOutput->getErrorCount());
        
        $errors = $cssValidatorOutput->getErrors();
        $expectedErrorValues = $this->getExpectedErrorValuesByRefDomain(array(
            'two.example.com',
            'three.example.com'
        ));
        
        foreach ($errors as $errorIndex => $error) {            
            /* @var $error \webignition\CssValidatorOutput\Message\Error */            
            $this->assertEquals($expectedErrorValues[$errorIndex]['ref'], $error->getRef());
            $this->assertEquals($expectedErrorValues[$errorIndex]['line'], $error->getLineNumber());
            $this->assertEquals($expectedErrorValues[$errorIndex]['context'], $error->getContext());
            $this->assertEquals($expectedErrorValues[$errorIndex]['body'], $error->getBody());               
        }
    }
    

    public function testRefDomainsToIgnoreIgnoreTwo() {        
        $parser = new Parser();
        $parser->setRawOutput($this->rawOutput);        
        $parser->setIgnoreWarnings(true);
        $parser->setRefDomainsToIgnore(array('two.example.com'));
        
        $cssValidatorOutput = $parser->getOutput();
        $this->assertInstanceOf('webignition\CssValidatorOutput\CssValidatorOutput', $cssValidatorOutput);     
        
        $this->assertEquals(4, $cssValidatorOutput->getErrorCount());
        
        $errors = $cssValidatorOutput->getErrors();
        $expectedErrorValues = $this->getExpectedErrorValuesByRefDomain(array(
            'one.example.com',
            'three.example.com'
        ));
        
        foreach ($errors as $errorIndex => $error) {            
            /* @var $error \webignition\CssValidatorOutput\Message\Error */            
            $this->assertEquals($expectedErrorValues[$errorIndex]['ref'], $error->getRef());
            $this->assertEquals($expectedErrorValues[$errorIndex]['line'], $error->getLineNumber());
            $this->assertEquals($expectedErrorValues[$errorIndex]['context'], $error->getContext());
            $this->assertEquals($expectedErrorValues[$errorIndex]['body'], $error->getBody());               
        }
    }    

    
    public function testRefDomainsToIgnoreIgnoreOneAndTwo() {        
        $parser = new Parser();
        $parser->setRawOutput($this->rawOutput);        
        $parser->setIgnoreWarnings(true);
        $parser->setRefDomainsToIgnore(array('one.example.com', 'two.example.com'));
        
        $cssValidatorOutput = $parser->getOutput();
        $this->assertInstanceOf('webignition\CssValidatorOutput\CssValidatorOutput', $cssValidatorOutput);     
        
        $this->assertEquals(2, $cssValidatorOutput->getErrorCount());
        
        $errors = $cssValidatorOutput->getErrors();
        $expectedErrorValues = $this->getExpectedErrorValuesByRefDomain(array(
            'three.example.com'
        ));
        
        foreach ($errors as $errorIndex => $error) {            
            /* @var $error \webignition\CssValidatorOutput\Message\Error */            
            $this->assertEquals($expectedErrorValues[$errorIndex]['ref'], $error->getRef());
            $this->assertEquals($expectedErrorValues[$errorIndex]['line'], $error->getLineNumber());
            $this->assertEquals($expectedErrorValues[$errorIndex]['context'], $error->getContext());
            $this->assertEquals($expectedErrorValues[$errorIndex]['body'], $error->getBody());               
        }
    }     
    
    /**
     * 
     * @param array $refDomains
     * @return array
     */
    private function getExpectedErrorValuesByRefDomain($refDomains = array()) {
        $expectedErrorValues = array();
        foreach ($this->expectedErrorValues as $errorValueSet) {
            $errorValueSetRefUrl = new NormalisedUrl($errorValueSet['ref']);
            if (in_array((string)$errorValueSetRefUrl->getHost(), $refDomains)) {
                $expectedErrorValues[] = $errorValueSet;
            }
        }
        
        return $expectedErrorValues;
    }
}