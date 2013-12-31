<?php

namespace webignition\Tests\CssValidatorOutput\Parser\GetOutput\Exception;

use webignition\Tests\CssValidatorOutput\BaseTest;
use webignition\CssValidatorOutput\Parser as Parser;

class ExceptionOutputTest extends BaseTest {
    
    public function setUp() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
    }    
    
    public function testParseFileNotFoundException() {        
        $this->assertIsExceptionOfType(array(
            'fixture' => 'java.io.FileNotFoundException.http-404.txt',
            'exceptionCheck' => 'isHttp404'
        ));     
    }    
    
    public function testParse401ProtocolException() {
        $this->assertIsExceptionOfType(array(
            'fixture' => 'java.net.ProtocolException.http-401.txt',
            'exceptionCheck' => 'isHttp401'
        ));    
    }
    
    public function testParseIllegalUrlExceptionOutput() {
        $this->assertIsExceptionOfType(array(
            'fixture' => 'java.lang.IllegalArgumentException.illegalurl.txt',
            'exceptionCheck' => 'isIllegalUrl'
        ));      
    }    
    
    public function testParseInternalServerErrorOutput() {
        $this->assertIsExceptionOfType(array(
            'fixture' => 'java.io.FileNotFoundException.http-500.txt',
            'exceptionCheck' => 'isHttp500'
        ));      
    }   
    
    public function testParseSslExceptionErrorOutput() {
        $this->assertIsExceptionOfType(array(
            'fixture' => 'javax.net.ssl.SSLException.txt',
            'exceptionCheck' => 'isSslException'
        ));      
    }    
    
    public function testParseUnknownMimeTypeError() {
        $this->assertIsExceptionOfType(array(
            'fixture' => 'java.io.IOException.unknownmimetype.txt',
            'exceptionCheck' => 'isUnknownMimeType'
        ));
    }    
    
    
    public function testParseUnknownHostErrorOutput() {
        $this->assertIsExceptionOfType(array(
            'fixture' => 'java.net.UnknownHostException.txt',
            'exceptionCheck' => 'isUnknownHost'
        ));     
    }     
    
    
    public function testParseUnknownFileErrorOutput() {
        $this->assertIsExceptionOfType(array(
            'fixture' => 'java.lang.Exception.unknownfile.txt',
            'exceptionCheck' => 'isUnknownFile'
        ));     
    }     
    
    public function testParseUnknownExceptionOutput() {
        $this->assertIsExceptionOfType(array(
            'fixture' => 'UnknownException.txt',
            'exceptionCheck' => 'isUnknown'
        ));     
    }    
    
    private function assertIsExceptionOfType($properties) {
        $rawOutput = $this->getFixture('Exception/' . $properties['fixture']);
        
        $parser = new Parser();
        $parser->setRawOutput($rawOutput);
        
        $cssValidatorOutput = $parser->getOutput();                
        
        $this->assertInstanceOf('webignition\CssValidatorOutput\CssValidatorOutput', $cssValidatorOutput);
        $this->assertTrue($cssValidatorOutput->hasException());
        $this->assertTrue($cssValidatorOutput->getException()->$properties['exceptionCheck']());        
    }
   
}