<?php

namespace webignition\Tests\CssValidatorOutput\Parser\GetOutput\Exception;

use webignition\Tests\CssValidatorOutput\BaseTest;

class ExceptionOutputTest extends BaseTest {
    
    public function setUp() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
    }    
    
    public function testParseFileNotFoundException() {        
        $this->assertIsExceptionOfType(array(
            'fixture' => 'java.io.FileNotFoundException.http-404.txt',
            'exceptionCheck' => array(
                'isHttp404' => true,
                'isHttpError' => true,
                'isHttpClientError' => true,
                'getHttpStatusCode' => 404
            )
        ));     
    }    
    
    public function testParse401ProtocolException() {
        $this->assertIsExceptionOfType(array(
            'fixture' => 'java.net.ProtocolException.http-401.txt',
            'exceptionCheck' => array(
                'isHttp401' => true,
                'isHttpError' => true,
                'isHttpClientError' => true,
                'getHttpStatusCode' => 401
            )
        ));    
    }
    
    public function testParseIllegalUrlExceptionOutput() {
        $this->assertIsExceptionOfType(array(
            'fixture' => 'java.lang.IllegalArgumentException.illegalurl.txt',
            'exceptionCheck' => array(
                'isCurl3' => true,
                'isCurlError' => true,
                'getCurlCode' => 3
            )            
        ));      
    }    
    
    public function testParseInternalServerErrorOutput() {
        $this->assertIsExceptionOfType(array(
            'fixture' => 'java.io.FileNotFoundException.http-500.txt',
            'exceptionCheck' => array(
                'isHttp500' => true,
                'isHttpError' => true,
                'isHttpServerError' => true,
                'getHttpStatusCode' => 500
            )
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
            'exceptionCheck' => array(
                'isCurl6' => true,
                'isCurlError' => true,
                'getCurlCode' => 6
            )
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
        $parser = $this->getParser(array(
            'rawOutput' => $this->getFixture('Exception/' . $properties['fixture'])
        ));        
        $cssValidatorOutput = $parser->getOutput();                
        
        $this->assertInstanceOf('webignition\CssValidatorOutput\CssValidatorOutput', $cssValidatorOutput);
        $this->assertTrue($cssValidatorOutput->hasException());
        
        if (is_string($properties['exceptionCheck'])) {
            $this->assertTrue($cssValidatorOutput->getException()->$properties['exceptionCheck']());        
        }
        
        if (is_array($properties['exceptionCheck'])) {
            foreach ($properties['exceptionCheck'] as $key => $value) {
                if (is_int($key)) {
                    $this->assertTrue($cssValidatorOutput->getException()->$value());
                } else {
                    $this->assertEquals($value, $cssValidatorOutput->getException()->$key());
                }
                
                
            }
        }        
    }
   
}