<?php
namespace webignition\Tests\CssValidatorOutput\Parser\GetOutput;

use webignition\Tests\CssValidatorOutput\BaseTest;
use webignition\CssValidatorOutput\CssValidatorOutput;

class ValidOutputTest extends BaseTest {    
    
    /**
     *
     * @var CssValidatorOutput
     */
    private $cssValidatorOutput;
    
    private $expectedErrorValues = array(
        array(
            'ref' => 'http://blog.simplytestable.com/vendor/twitter-bootstrap/bootstrap/css/bootstrap1.css',
            'line' => 28,
            'context' => 'audio, canvas, video',
            'message' => 'Parse Error
		*display: inline;'
        ),
        array(
            'ref' => 'http://blog.simplytestable.com/vendor/twitter-bootstrap/bootstrap/css/bootstrap2.css',
            'line' => 38,
            'context' => 'html',
            'message' => 'Property -webkit-text-size-adjust doesn&#39;t exist : 
		100%'
        ),
        array(
            'ref' => 'http://blog.simplytestable.com/vendor/twitter-bootstrap/bootstrap/css/bootstrap3.css',
            'line' => 141,
            'context' => '.hide-text',
            'message' => 'Value Error :  font (nullfonts.html#propdef-font)
		0 is not a font-weight value : 
		0 / 0 a'
        )        
    );
    
    private $expectedWarningValues = array(
        array(
            'level' => 0,
            'line' => 5,
            'context' => '',
            'message' => 'You should add a \'type\' attribute with a value of \'text/css\' to the \'link\' element'
        ),
        array(
            'level' => 0,
            'line' => 6,
            'context' => '',
            'message' => 'You should add a \'type\' attribute with a value of \'text/css\' to the \'link\' element'
        )      
    );    
    
    public function setUp() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
        
        $parser = $this->getParser(array(
            'rawOutput' => $this->getFixture('output01.txt')
        ));     
        
        $this->cssValidatorOutput = $parser->getOutput();        
        $this->assertInstanceOf('webignition\CssValidatorOutput\CssValidatorOutput', $this->cssValidatorOutput);
    }
    
    public function testOptionsAreParsed() {
        $options = $this->cssValidatorOutput->getOptions();        
        $this->assertInstanceOf('webignition\CssValidatorOutput\Options\Options', $options);

        $this->assertFalse($options->getVendorExtensionIssuesAsWarnings());
        $this->assertEquals('ucn', $options->getOutputFormat());        
        $this->assertEquals('en', $options->getLanguage());
        $this->assertEquals(2, $options->getWarningLevel());
        $this->assertEquals('all', $options->getMedium());
        $this->assertEquals('css3', $options->getProfile());       
    }    
    
    public function testDateTimeIsParsed() {        
        $datetime = $this->cssValidatorOutput->getDateTime();
        $this->assertInstanceOf('\DateTime', $datetime);
        
        $this->assertEquals('2012-12-27T04:09:39+00:00', $datetime->format('c'));      
    }    
    
    public function testGetMessageCounts() {
        $this->assertEquals(5, $this->cssValidatorOutput->getMessageCount());
        $this->assertEquals(3, $this->cssValidatorOutput->getErrorCount());
        $this->assertEquals(2, $this->cssValidatorOutput->getWarningCount());      
    }    

    public function testParsedErrors() {        
        $errors = $this->cssValidatorOutput->getErrors();
        $this->assertEquals(3, count($errors));
        
        foreach ($errors as $errorIndex => $error) {
            /* @var $error \webignition\CssValidatorOutput\Message\Error */
            $this->assertInstanceOf('\webignition\CssValidatorOutput\Message\Error', $error);

            $this->assertEquals($this->expectedErrorValues[$errorIndex]['ref'], $error->getRef());
            $this->assertEquals($this->expectedErrorValues[$errorIndex]['line'], $error->getLineNumber());
            $this->assertEquals($this->expectedErrorValues[$errorIndex]['context'], $error->getContext());
            $this->assertEquals($this->expectedErrorValues[$errorIndex]['message'], $error->getMessage());            
        }       
    }    
    
    public function testParsedWarnings() {                
        $warnings = $this->cssValidatorOutput->getWarnings();
        $this->assertEquals(2, count($warnings));        
        
        foreach ($warnings as $warningIndex => $warning) {
            /* @var $error \webignition\CssValidatorOutput\Message\Warning */
            $this->assertInstanceOf('\webignition\CssValidatorOutput\Message\Warning', $warning);

            $this->assertEquals($this->expectedWarningValues[$warningIndex]['level'], $warning->getLevel());
            $this->assertEquals($this->expectedWarningValues[$warningIndex]['line'], $warning->getLineNumber());
            $this->assertEquals($this->expectedWarningValues[$warningIndex]['context'], $warning->getContext());
            $this->assertEquals($this->expectedWarningValues[$warningIndex]['message'], $warning->getMessage());            
        }          
    }
}