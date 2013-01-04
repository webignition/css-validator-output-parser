<?php

class CssValidatorOutputWarningTest extends BaseTest {
    
    /**
     *
     * @var \webignition\CssValidatorOutput\Message\Warning
     */
    private $warning;
    
    public function setUp() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
        $this->warning = new \webignition\CssValidatorOutput\Message\Warning();
    }  
    

    public function testDefaultLevel() {
        $this->assertEquals(0, $this->warning->getLevel()); 
    }    

    public function testSetNegativeLevel() {
        $this->warning->setLevel(-1);
        $this->assertEquals(0, $this->warning->getLevel()); 
    }    
    
    public function testSetPositiveLevel() {
        $this->warning->setLevel(1);
        $this->assertEquals(1, $this->warning->getLevel()); 
    }    
    
    public function testSetNonIntegerLineNumber() {
        $this->warning->setLevel('foobar');
        $this->assertEquals(0, $this->warning->getLevel()); 
    }    
    
    public function testGetDefaultType() {      
        $this->assertTrue($this->warning->isWarning());
    }
    
    public function tetGetSerializedType() {        
        $this->assertEquals('warning', $this->warning->getSerializedType());        
    }    
   
}