<?php

namespace webignition\CssValidatorOutput\Options;

class Parser {
    
    /**
     *
     * @var string
     */
    private $optionsOutput = '';
    
    /**
     *
     * @var Options
     */
    private $options = null;
    
    
    /**
     * 
     * @param string $optionsOutput
     * @return \webignition\CssValidatorOutput\Options\Parser
     */
    public function setOptionsOutput($optionsOutput) {
        $this->optionsOutput = $optionsOutput;
        return $this;
    }
    
    
    /**
     * 
     * @return Options
     */
    public function getOptions() {
        if (is_null($this->options)) {
            $this->parse();
        }
        
        return $this->options;
    }
    
    
    private function parse() {        
        if (!$this->isRawOutputValid()) {
            return false;
        }
        
        $this->options = new Options();        
        
        $optionsValuesString = substr($this->optionsOutput, 1, strlen($this->optionsOutput) - 2);
        $optionKeyValuePairs = explode(',', $optionsValuesString);
        
        foreach ($optionKeyValuePairs as $optionKeyValuePair) {
            $this->parseOptionKeyValuePair($optionKeyValuePair);
        }
    }
    
    
    /**
     * 
     * @return boolean
     */
    private function isRawOutputValid() {
        $pattern = '/{(([^=]+=[a-z0-9]+,\s?)+[^=]+=[a-z0-9]+)|([^=]+=[a-z0-9]+)}/';
        
        return preg_match($pattern, $this->optionsOutput) > 0;
    }
    
    
    private function parseOptionKeyValuePair($optionKeyValuePair) {
        $keyValue = explode('=', $optionKeyValuePair);
        $key = trim($keyValue[0]);
        $value = trim($keyValue[1]);
        
        switch ($key) {
            case 'vextwarning':
                $this->options->setVendorExtensionIssuesAsWarnings(filter_var($value, FILTER_VALIDATE_BOOLEAN));                
                break;
            
            case 'output':
                $this->options->setOutputFormat($value);
                break;
            
            case 'lang':
                $this->options->setLanguage($value);
                break;
            
            case 'warning':
                $warningLevel = filter_var($value, FILTER_VALIDATE_INT, array('options'=> array(
                    'min_range' => 0,
                    'default' => 2
                 )));
                
                $this->options->setWarningLevel($warningLevel);
                break;

            case 'medium':
                $this->options->setMedium($value);
                break;
            
            case 'profile':
                $this->options->setProfile($value);
                break;            
        }
    }
    
    
}