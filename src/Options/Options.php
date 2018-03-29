<?php

namespace webignition\CssValidatorOutput\Options;

class Options {
    
    /**
     *      * {vextwarning=false, output=text, lang=en, warning=2, medium=all, profile=css3}
     */
    
    /**
     *
     * @var boolean
     */
    private $vendorExtensionIssuesAsWarnings = false;
    
    /**
     *
     * @var string
     */
    private $outputFormat = 'text';
    
    /**
     *
     * @var string
     */
    private $language = 'en';
    
    /**
     *
     * @var int
     */
    private $warningLevel = 2;
    
    /**
     *
     * @var string
     */
    private $medium = 'all';
    
    /**
     *
     * @var string
     */
    private $profile = 'css3';
    
    
    /**
     * 
     * @param boolean $vendorExtensionIssuesAsWarnings
     * @return \webignition\CssValidatorOutput\Options\Options
     */
    public function setVendorExtensionIssuesAsWarnings($vendorExtensionIssuesAsWarnings) {
        $this->vendorExtensionIssuesAsWarnings = $vendorExtensionIssuesAsWarnings;
        return $this;
    }
    
    /**
     * 
     * @return boolean
     */
    public function getVendorExtensionIssuesAsWarnings() {
        return $this->vendorExtensionIssuesAsWarnings;
    }
    
    
    /**
     * 
     * @param string $outputFormat
     * @return \webignition\CssValidatorOutput\Options\Options
     */
    public function setOutputFormat($outputFormat) {
        $this->outputFormat = $outputFormat;
        return $this;
    }
    
    
    /**
     * 
     * @return string
     */
    public function getOutputFormat() {
        return $this->outputFormat;
    }
    
    
    /**
     * 
     * @param string $language
     * @return \webignition\CssValidatorOutput\Options\Options
     */
    public function setLanguage($language) {
        $this->language = $language;
        return $this;
    }
    
    
    /**
     * 
     * @return string
     */
    public function getLanguage() {
        return $this->language;
    }
    
    
    /**
     * 
     * @param int $warningLevel
     * @return \webignition\CssValidatorOutput\Options\Options
     */
    public function setWarningLevel($warningLevel) {
        $this->warningLevel = $warningLevel;
        return $this;
    }
    
    
    /**
     * 
     * @return int
     */
    public function getWarningLevel() {
        return $this->warningLevel;
    }
    
    
    /**
     * 
     * @param string $medium
     * @return \webignition\CssValidatorOutput\Options\Options
     */
    public function setMedium($medium) {
        $this->medium = $medium;
        return $this;
    }
    
    
    /**
     * 
     * @return string
     */
    public function getMedium() {
        return $this->medium;
    }
    
    
    /**
     * 
     * @param string $profile
     * @return \webignition\CssValidatorOutput\Options\Options
     */
    public function setProfile($profile) {
        $this->profile = $profile;
        return $this;
    }
    
    
    /**
     * 
     * @return string
     */
    public function getProfile() {
        return $this->profile;
    }
    
    
    /**
     * 
     * @return string
     */
    public function __toString() {
        return '{vextwarning='.($this->getVendorExtensionIssuesAsWarnings() ? 'true' : 'false').', output='.$this->getOutputFormat().', lang='.$this->getLanguage().', warning='.$this->getWarningLevel().', medium='.$this->getMedium().', profile='.$this->getProfile().'}';
    }
    
    
} 