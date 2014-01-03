<?php
namespace webignition\Tests\CssValidatorOutput\Parser\GetOutput;

use webignition\Tests\CssValidatorOutput\BaseTest;
use webignition\CssValidatorOutput\CssValidatorOutput;

class GetRawValuesTest extends BaseTest {    
    
    public function setUp() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
    }    

    
    public function testGetRawHeader() {
        $configuration = new \webignition\CssValidatorOutput\Parser\Configuration();
        $configuration->setRawOutput($this->getFixture('output01.txt'));
        
        $parser = new \webignition\CssValidatorOutput\Parser\Parser();
        $parser->setConfiguration($configuration);
        $parser->getOutput();
        
        $this->assertEquals('{vextwarning=false, output=ucn, lang=en, warning=2, medium=all, profile=css3}', $parser->getRawHeader());        
    }
    
    public function testGetRawBody() {
        $configuration = new \webignition\CssValidatorOutput\Parser\Configuration();
        $configuration->setRawOutput($this->getFixture('example.txt'));
        
        $parser = new \webignition\CssValidatorOutput\Parser\Parser();
        $parser->setConfiguration($configuration);
        $parser->getOutput();
        
        $this->assertEquals('<?xml version=\'1.0\' encoding="utf-8"?>
<observationresponse xmlns="http://www.w3.org/2009/10/unicorn/observationresponse" ref="http://example.com" date="2012-12-27T04:09:39Z" xml:lang="en">
 
<message type="error" ref="http://example.com/example.css">
	<context line="1">example</context>
	<title>
		Parse Error
	</title>
</message>

</observationresponse>', $parser->getRawBody());
        
    }    
}