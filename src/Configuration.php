<?php

namespace webignition\CssValidatorOutput\Parser;

class Configuration
{
    const KEY_IGNORE_WARNINGS = 'ignore-warnings';
    const KEY_IGNORE_VENDOR_EXTENSION_ISSUES = 'ignore-vendor-extension-issues';
    const KEY_IGNORE_FALSE_DATA_URL_MESSAGES = 'ignore-false-data-url-messages';
    const KEY_REPORT_VENDOR_EXTENSION_ISSUES_AS_WARNINGS = 'report-vendor-extension-issues-as-warnings';

    const DEFAULT_IGNORE_WARNINGS = false;
    const DEFAULT_IGNORE_VENDOR_EXTENSION_ISSUES = false;
    const DEFAULT_IGNORE_FALSE_DATA_URL_MESSAGES = false;
    const DEFAULT_REPORT_VENDOR_EXTENSION_ISSUES_AS_WARNINGS = false;

    /**
     * @var bool
     */
    private $ignoreWarnings = self::DEFAULT_IGNORE_WARNINGS;

    /**
     * @var bool
     */
    private $ignoreVendorExtensionIssues = self::DEFAULT_IGNORE_VENDOR_EXTENSION_ISSUES;

    /**
     * @var bool
     */
    private $ignoreFalseImageDataUrlMessages = self::DEFAULT_IGNORE_FALSE_DATA_URL_MESSAGES;

    /**
     * @var bool
     */
    private $reportVendorExtensionIssuesAsWarnings = self::DEFAULT_REPORT_VENDOR_EXTENSION_ISSUES_AS_WARNINGS;

    /**
     * @param array $configurationValues
     */
    public function __construct(array $configurationValues = [])
    {
        if (array_key_exists(self::KEY_IGNORE_WARNINGS, $configurationValues)) {
            $this->ignoreWarnings = filter_var(
                $configurationValues[self::KEY_IGNORE_WARNINGS],
                FILTER_VALIDATE_BOOLEAN
            );
        }

        if (array_key_exists(self::KEY_IGNORE_VENDOR_EXTENSION_ISSUES, $configurationValues)) {
            $this->ignoreVendorExtensionIssues = filter_var(
                $configurationValues[self::KEY_IGNORE_VENDOR_EXTENSION_ISSUES],
                FILTER_VALIDATE_BOOLEAN
            );
        }

        if (array_key_exists(self::KEY_IGNORE_FALSE_DATA_URL_MESSAGES, $configurationValues)) {
            $this->ignoreFalseImageDataUrlMessages = filter_var(
                $configurationValues[self::KEY_IGNORE_FALSE_DATA_URL_MESSAGES],
                FILTER_VALIDATE_BOOLEAN
            );
        }

        if (array_key_exists(self::KEY_REPORT_VENDOR_EXTENSION_ISSUES_AS_WARNINGS, $configurationValues)) {
            $this->reportVendorExtensionIssuesAsWarnings = filter_var(
                $configurationValues[self::KEY_REPORT_VENDOR_EXTENSION_ISSUES_AS_WARNINGS],
                FILTER_VALIDATE_BOOLEAN
            );
        }
    }

    public function getReportVendorExtensionIssuesAsWarnings(): bool
    {
        return $this->reportVendorExtensionIssuesAsWarnings;
    }

    public function getIgnoreFalseImageDataUrlMessages(): bool
    {
        return $this->ignoreFalseImageDataUrlMessages;
    }

    public function getIgnoreWarnings(): bool
    {
        return $this->ignoreWarnings;
    }

    public function getIgnoreVendorExtensionIssues(): bool
    {
        return $this->ignoreVendorExtensionIssues;
    }
}
