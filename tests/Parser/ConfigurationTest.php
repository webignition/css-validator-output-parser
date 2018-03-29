<?php
namespace webignition\Tests\CssValidatorOutput\Parser;

use webignition\Tests\CssValidatorOutput\BaseTest;
use webignition\CssValidatorOutput\Parser\Configuration;

class ConfigurationTest extends BaseTest {
    
    /**
     *
     * @var Configuration
     */
    private $configuration;
    
    
    public function setUp() {
        $this->configuration = new Configuration();
    }
    
    
    public function testDefaultRawOutputIsBlankString() {
        $this->assertEquals('', $this->configuration->getRawOutput());
    }
    
    
    public function testSetGetRawOutput() {
        $this->assertSetGetProperty(array(
            'name' => 'rawOutput',
            'value' => 'foo'
        ));
    }
    
    
    public function testDefaultIgnoreWarningsIsFalse() {
        $this->assertFalse($this->configuration->getIgnoreWarnings());
    }
    
    
    public function testSetGetIgnoreWarningsFalse() {
        $this->assertSetGetProperty(array(
            'name' => 'ignoreWarnings',
            'value' => false
        ));        
    }
    
    public function testSetGetIgnoreWarningsTrue() {
        $this->assertSetGetProperty(array(
            'name' => 'ignoreWarnings',
            'value' => true
        ));        
    } 
    
    
    public function testDefaultRefDomainsToIgnoreIsEmptyArray() {
        $this->assertEquals(array(), $this->configuration->getRefDomainsToIgnore());
    }
    
    
    public function testSetGetRefDomainsToIgnore() {
        $this->assertSetGetProperty(array(
            'name' => 'refDomainsToIgnore',
            'value' => array(
                'foo',
                'bar'
            )
        ));        
    }
    
    
    public function testDefaultIgnoreVendorExtensionIssuesIsFalse() {
        $this->assertFalse($this->configuration->getIgnoreVendorExtensionIssues());
    }
    
    
    public function testSetGetIgnoreVendorExtensionIssuesFalse() {
        $this->assertSetGetProperty(array(
            'name' => 'ignoreVendorExtensionIssues',
            'value' => false
        ));        
    }
    
    public function testSetGetIgnoreVendorExtensionIssuesTrue() {
        $this->assertSetGetProperty(array(
            'name' => 'ignoreVendorExtensionIssues',
            'value' => true
        ));        
    }     
    
    public function testDefaultIgnoreFalseImageDataUrlMessagesIsFalse() {
        $this->assertFalse($this->configuration->getIgnoreVendorExtensionIssues());
    }
    
    
    public function testSetGetIgnoreFalseImageDataUrlMessagesFalse() {
        $this->assertSetGetProperty(array(
            'name' => 'ignoreFalseImageDataUrlMessages',
            'value' => false
        ));        
    }
    
    public function testSetGetIgnoreFalseImageDataUrlMessagesTrue() {
        $this->assertSetGetProperty(array(
            'name' => 'ignoreFalseImageDataUrlMessages',
            'value' => true
        ));        
    }     
    
    
    public function assertSetGetProperty($properties) {
        $setMethodName = 'set' . $properties['name'];
        $getMethodName = 'get' . $properties['name'];

        $this->configuration->$setMethodName($properties['value']);
        $this->assertEquals($properties['value'], $this->configuration->$getMethodName());    
    }
}