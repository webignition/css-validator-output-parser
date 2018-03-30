<?php

namespace webignition\CssValidatorOutput\Options;

class Options
{
    /**
     * @var bool
     */
    private $vendorExtensionIssuesAsWarnings = false;

    /**
     * @var string
     */
    private $outputFormat = 'text';

    /**
     * @var string
     */
    private $language = 'en';

    /**
     * @var int
     */
    private $warningLevel = 2;

    /**
     * @var string
     */
    private $medium = 'all';

    /**
     * @var string
     */
    private $profile = 'css3';

    public function __construct(
        $vendorExtensionIssuesAsWarnings,
        $outputFormat,
        $language,
        $warningLevel,
        $medium,
        $profile
    ) {
        $this->vendorExtensionIssuesAsWarnings = $vendorExtensionIssuesAsWarnings;
        $this->outputFormat = $outputFormat;
        $this->language = $language;
        $this->warningLevel = $warningLevel;
        $this->medium = $medium;
        $this->profile = $profile;
    }

    /**
     * @return bool
     */
    public function getVendorExtensionIssuesAsWarnings()
    {
        return $this->vendorExtensionIssuesAsWarnings;
    }

    /**
     * @return string
     */
    public function getOutputFormat()
    {
        return $this->outputFormat;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @return int
     */
    public function getWarningLevel()
    {
        return $this->warningLevel;
    }

    /**
     * @return string
     */
    public function getMedium()
    {
        return $this->medium;
    }

    /**
     * @return string
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            '{vextwarning=%s, output=%s, lang=%s, warning=%s, medium=%s, profile=%s}',
            ($this->vendorExtensionIssuesAsWarnings ? 'true' : 'false'),
            $this->outputFormat,
            $this->language,
            $this->warningLevel,
            $this->medium,
            $this->profile
        );
    }
}
