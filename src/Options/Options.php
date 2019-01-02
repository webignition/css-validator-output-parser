<?php

namespace webignition\CssValidatorOutput\Options;

class Options
{
    private $vendorExtensionIssuesAsWarnings = false;
    private $outputFormat = 'text';
    private $language = 'en';
    private $warningLevel = 2;
    private $medium = 'all';
    private $profile = 'css3';

    public function __construct(
        bool $vendorExtensionIssuesAsWarnings,
        string $outputFormat,
        string $language,
        int $warningLevel,
        string $medium,
        string $profile
    ) {
        $this->vendorExtensionIssuesAsWarnings = $vendorExtensionIssuesAsWarnings;
        $this->outputFormat = $outputFormat;
        $this->language = $language;
        $this->warningLevel = $warningLevel;
        $this->medium = $medium;
        $this->profile = $profile;
    }

    public function getVendorExtensionIssuesAsWarnings(): bool
    {
        return $this->vendorExtensionIssuesAsWarnings;
    }

    public function getOutputFormat(): string
    {
        return $this->outputFormat;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function getWarningLevel(): int
    {
        return $this->warningLevel;
    }

    public function getMedium(): string
    {
        return $this->medium;
    }

    public function getProfile(): string
    {
        return $this->profile;
    }

    public function __toString(): string
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
