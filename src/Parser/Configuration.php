<?php

namespace webignition\CssValidatorOutput\Parser;

use webignition\CssValidatorOutput\Sanitizer;

class Configuration
{
    const KEY_IGNORE_WARNINGS = 'ignore-warnings';
    const KEY_REF_DOMAINS_TO_IGNORE = 'ref-domains-to-ignore';
    const KEY_IGNORE_VENDOR_EXTENSION_ISSUES = 'ignore-vendor-extension-issues';
    const KEY_IGNORE_FALSE_DATA_URL_MESSAGES = 'ignore-false-data-url-messages';
    const KEY_REPORT_VENDOR_EXTENSION_ISSUES_AS_WARNINGS = 'report-vendor-extension-issues-as-warnings';

    const DEFAULT_IGNORE_WARNINGS = false;
    const DEFAULT_REF_DOMAINS_TO_IGNORE = [];
    const DEFAULT_IGNORE_VENDOR_EXTENSION_ISSUES = false;
    const DEFAULT_IGNORE_FALSE_DATA_URL_MESSAGES = false;
    const DEFAULT_REPORT_VENDOR_EXTENSION_ISSUES_AS_WARNINGS = false;

    /**
     * @var string
     */
    private $rawOutput = '';

    /**
     * @var bool
     */
    private $ignoreWarnings = self::DEFAULT_IGNORE_WARNINGS;

    /**
     * @var array
     */
    private $refDomainsToIgnore = self::DEFAULT_REF_DOMAINS_TO_IGNORE;

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

        if (array_key_exists(self::KEY_REF_DOMAINS_TO_IGNORE, $configurationValues)) {
            $refDomainsToIgnore = $configurationValues[self::KEY_REF_DOMAINS_TO_IGNORE];
            if (is_array($refDomainsToIgnore)) {
                $this->refDomainsToIgnore = $refDomainsToIgnore;
            }
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

    /**
     * @return bool
     */
    public function getReportVendorExtensionIssuesAsWarnings()
    {
        return $this->reportVendorExtensionIssuesAsWarnings;
    }

    /**
     * @return bool
     */
    public function getIgnoreFalseImageDataUrlMessages()
    {
        return $this->ignoreFalseImageDataUrlMessages;
    }

    /**
     * @return bool
     */
    public function getIgnoreWarnings()
    {
        return $this->ignoreWarnings;
    }

    /**
     * @return array
     */
    public function getRefDomainsToIgnore()
    {
        return $this->refDomainsToIgnore;
    }

    /**
     * @return bool
     */
    public function getIgnoreVendorExtensionIssues()
    {
        return $this->ignoreVendorExtensionIssues;
    }

    /**
     * @param string $rawOutput
     */
    public function setRawOutput($rawOutput)
    {
        $sanitizer = new Sanitizer();
        $this->rawOutput = trim($sanitizer->getSanitizedOutput($rawOutput));
    }

    /**
     * @return string
     */
    public function getRawOutput()
    {
        return $this->rawOutput;
    }
}
