<?php

namespace webignition\CssValidatorOutput\Parser;

use webignition\CssValidatorOutput\Sanitizer;

class Configuration {
    
    /**
     *
     * @var string
     */
    private $rawOutput = '';

    
    /**
     *
     * @var boolean
     */
    private $ignoreWarnings = false;
    
    
    /**
     *
     * @var array
     */
    private $refDomainsToIgnore = array();
    
    
    /**
     *
     * @var boolean
     */
    private $ignoreVendorExtensionIssues = false;
    
    
    /**
     *
     * @var boolean
     */
    private $ignoreFalseImageDataUrlMessages = false;
    
    
    /**
     *
     * @var boolean
     */
    private $reportVendorExtensionIssuesAsWarnings = false;
    
    
    /**
     * 
     * @param boolean $reportVendorExtensionIssuesAsWarnings
     */
    public function setReportVendorExtensionIssuesAsWarnings($reportVendorExtensionIssuesAsWarnings) {
        $this->reportVendorExtensionIssuesAsWarnings = $reportVendorExtensionIssuesAsWarnings;
    }
    
    
    /**
     * 
     * @return boolean
     */
    public function getReportVendorExtensionIssuesAsWarnings() {
        return $this->reportVendorExtensionIssuesAsWarnings;
    }
    
    
    /**
     * 
     * @param boolean $ignoreFalseBase64BackgroundImageMessages
     */
    public function setIgnoreFalseImageDataUrlMessages($ignoreFalseImageDataUrlMessages) {
        $this->ignoreFalseImageDataUrlMessages = $ignoreFalseImageDataUrlMessages;
    }
    
    
    /**
     * @return bool
     */
    public function getIgnoreFalseImageDataUrlMessages() {
        return $this->ignoreFalseImageDataUrlMessages;
    }    
    
    
    /**
     * 
     * @param boolean $ignoreWarnings
     * @return \webignition\CssValidatorOutput\CssValidatorOutput
     */
    public function setIgnoreWarnings($ignoreWarnings) {
        $this->ignoreWarnings = filter_var($ignoreWarnings, FILTER_VALIDATE_BOOLEAN);
        return $this;
    }
    
    
    /**
     * 
     * @return boolean
     */
    public function getIgnoreWarnings() {
        return $this->ignoreWarnings;
    }    
    
    
    /**
     * 
     * @param array $refDomainsToIgnore
     * @return \webignition\CssValidatorOutput\Parser
     */
    public function setRefDomainsToIgnore($refDomainsToIgnore) {
        if (!is_array($refDomainsToIgnore)) {
            $refDomainsToIgnore = array();
        }
        
        $this->refDomainsToIgnore = $refDomainsToIgnore;
        return $this;
    }
    
    
    /**
     * 
     * @return array
     */
    public function getRefDomainsToIgnore() {
        return $this->refDomainsToIgnore;
    }
    
    
    /**
     * 
     * @param boolean $ignoreVendorExtensionIssues
     * @return \webignition\CssValidatorOutput\Parser
     */
    public function setIgnoreVendorExtensionIssues($ignoreVendorExtensionIssues) {
        $this->ignoreVendorExtensionIssues = filter_Var($ignoreVendorExtensionIssues, FILTER_VALIDATE_BOOLEAN);
        return $this;
    }
    
    
    /**
     * 
     * @return boolean
     */
    public function getIgnoreVendorExtensionIssues() {
        return $this->ignoreVendorExtensionIssues;
    }
    
    
    /**
     * 
     * @param string $rawOutput
     */
    public function setRawOutput($rawOutput) {        
        $sanitizer = new Sanitizer();        
        $this->rawOutput = trim($sanitizer->getSanitizedOutput($rawOutput));
        $this->output = null;
    }
    
    
    /**
     * 
     * @return string
     */
    public function getRawOutput() {
        return $this->rawOutput;
    }
    
}