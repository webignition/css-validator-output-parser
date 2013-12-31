<?php
namespace webignition\Tests\CssValidatorOutput\Parser\GetOutput;

use webignition\Tests\CssValidatorOutput\BaseTest;
use webignition\NormalisedUrl\NormalisedUrl;

class RefDomainsToIgnoreTest extends BaseTest {
    
    private $rawOutput;
    
    private $expectedErrorValues = array(
        array(
            'ref' => 'http://one.example.com/style.css',
            'line' => 1,
            'context' => 'audio, canvas, video',
            'message' => 'one.1'
        ),       
        array(
            'ref' => 'http://two.example.com/style.css',
            'line' => 2,
            'context' => 'html',
            'message' => 'two.1'
        ), 
        array(
            'ref' => 'http://three.example.com/style.css',
            'line' => 3,
            'context' => '.hide-text',
            'message' => 'three.1'
        ), 
        array(
            'ref' => 'http://one.example.com/style.css',
            'line' => 4,
            'context' => 'audio, canvas, video',
            'message' => 'one.2'
        ), 
        array(
            'ref' => 'http://two.example.com/style.css',
            'line' => 5,
            'context' => 'html',
            'message' => 'two.2'
        ), 
        array(
            'ref' => 'http://three.example.com/style.css',
            'line' => 6,
            'context' => '.hide-text',
            'message' => 'three.2'
        ),         
    );    
    
    public function setUp() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
        $this->rawOutput = $this->getFixture('output05.txt');
    }
    
    public function testRefDomainsToIgnoreDefault() {        
        $parser = $this->getParser(array(
            'rawOutput' => $this->rawOutput,
            'configuration' => array(
                'ignoreWarnings' => true
            )
        ));
        
        $cssValidatorOutput = $parser->getOutput();  
        
        $this->assertEquals(6, $cssValidatorOutput->getErrorCount());
        
        $errors = $cssValidatorOutput->getErrors();
        
        foreach ($errors as $errorIndex => $error) {
            /* @var $error \webignition\CssValidatorOutput\Message\Error */            
            $this->assertEquals($this->expectedErrorValues[$errorIndex]['ref'], $error->getRef());
            $this->assertEquals($this->expectedErrorValues[$errorIndex]['line'], $error->getLineNumber());
            $this->assertEquals($this->expectedErrorValues[$errorIndex]['context'], $error->getContext());
            $this->assertEquals($this->expectedErrorValues[$errorIndex]['message'], $error->getMessage());               
        }
    }      
    
    public function testRefDomainsToIgnoreIgnoreOne() {        
        $parser = $this->getParser(array(
            'rawOutput' => $this->rawOutput,
            'configuration' => array(
                'ignoreWarnings' => true,
                'refDomainsToIgnore' => array(
                    'one.example.com'
                )
            )
        ));
        
        $cssValidatorOutput = $parser->getOutput();
        
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
            $this->assertEquals($expectedErrorValues[$errorIndex]['message'], $error->getMessage());               
        }
    }
    

    public function testRefDomainsToIgnoreIgnoreTwo() {        
        $parser = $this->getParser(array(
            'rawOutput' => $this->rawOutput,
            'configuration' => array(
                'ignoreWarnings' => true,
                'refDomainsToIgnore' => array(
                    'two.example.com'
                )
            )
        ));
        
        $cssValidatorOutput = $parser->getOutput();
        
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
            $this->assertEquals($expectedErrorValues[$errorIndex]['message'], $error->getMessage());               
        }
    }    

    
    public function testRefDomainsToIgnoreIgnoreOneAndTwo() {        
        $parser = $this->getParser(array(
            'rawOutput' => $this->rawOutput,
            'configuration' => array(
                'ignoreWarnings' => true,
                'refDomainsToIgnore' => array(
                    'one.example.com',
                    'two.example.com'
                )
            )
        ));
        
        $cssValidatorOutput = $parser->getOutput();
        
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
            $this->assertEquals($expectedErrorValues[$errorIndex]['message'], $error->getMessage());               
        }
    }     
    
    public function testRefDomainsToIgnoreAndHostlessDomainInRef() {
        $parser = $this->getParser(array(
            'rawOutput' => $this->getFixture('in-result-file-not-found.txt'),
            'configuration' => array(
                'ignoreWarnings' => true,
                'refDomainsToIgnore' => array(
                    'one.example.com',
                    'two.example.com'
                )
            )
        ));
        
        $cssValidatorOutput = $parser->getOutput();
        $this->assertInstanceOf('webignition\CssValidatorOutput\CssValidatorOutput', $cssValidatorOutput);             
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