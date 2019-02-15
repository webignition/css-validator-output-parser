<?php

namespace webignition\CssValidatorOutput\Parser;

class Flags
{
    const NONE = 0;
    const IGNORE_WARNINGS = 1;
    const IGNORE_VENDOR_EXTENSION_ISSUES = 2;
    const IGNORE_FALSE_IMAGE_DATA_URL_MESSAGES = 4;
    const REPORT_VENDOR_EXTENSION_ISSUES_AS_WARNINGS = 8;
}
