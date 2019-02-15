<?php

namespace webignition\CssValidatorOutput\Parser;

use webignition\CssValidatorOutput\Model\ExceptionOutput;

class ExceptionOutputParser
{
    public static function is(string $validatorBodyContent): bool
    {
        if (substr_count($validatorBodyContent, '</observationresponse>')) {
            return false;
        }

        return preg_match('/java.*Exception:/', $validatorBodyContent) > 0;
    }

    public static function parse(string $rawOutput): ExceptionOutput
    {
        $firstLine = substr($rawOutput, 0, strpos($rawOutput, "\n"));

        if (self::isFileNotFoundError($firstLine)) {
            return new ExceptionOutput(ExceptionOutput::TYPE_HTTP, '404');
        }

        if (self::isHttpAuthProtocolExceptionOutput($firstLine)) {
            return new ExceptionOutput(ExceptionOutput::TYPE_HTTP, '401');
        }

        if (self::isIllegalUrlError($firstLine)) {
            return new ExceptionOutput(ExceptionOutput::TYPE_CURL, '3');
        }

        if (self::isInternalServerError($firstLine)) {
            return new ExceptionOutput(ExceptionOutput::TYPE_HTTP, '500');
        }

        if (self::isSslExceptionOutput($firstLine)) {
            return new ExceptionOutput(ExceptionOutput::TYPE_SSL_ERROR);
        }

        if (self::isUnknownMimeTypeError($firstLine)) {
            $contentType = trim(substr($firstLine, strrpos($firstLine, ':') + 1));

            return new ExceptionOutput(ExceptionOutput::TYPE_UNKNOWN_CONTENT_TYPE, $contentType);
        }

        if (self::isUnknownHostError($firstLine)) {
            return new ExceptionOutput(ExceptionOutput::TYPE_UNKNOWN_HOST);
        }

        if (self::isUnknownFileExceptionOutput($firstLine)) {
            return new ExceptionOutput(ExceptionOutput::TYPE_UNKNOWN_FILE);
        }

        return new ExceptionOutput(ExceptionOutput::TYPE_UNKNOWN);
    }

    private static function isUnknownMimeTypeError(string $rawOutputFirstLine): bool
    {
        return preg_match('/Unknown mime type :/', $rawOutputFirstLine) > 0;
    }

    private static function isInternalServerError(string $rawOutputFirstLine): bool
    {
        if (!self::isFileNotFoundException($rawOutputFirstLine)) {
            return false;
        }

        return preg_match('/Internal Server Error/', $rawOutputFirstLine) > 0;
    }

    private static function isFileNotFoundError(string $rawOutputFirstLine): bool
    {
        if (!self::isFileNotFoundException($rawOutputFirstLine)) {
            return false;
        }

        return preg_match('/Not Found/', $rawOutputFirstLine) > 0;
    }

    private static function isFileNotFoundException(string $rawOutputFirstLine): bool
    {
        return preg_match('/^java\.io\.FileNotFoundException:/', $rawOutputFirstLine) > 0;
    }

    private static function isUnknownHostError(string $rawOutputFirstLine): bool
    {
        return preg_match('/^java\.net\.UnknownHostException:/', $rawOutputFirstLine) > 0;
    }

    private static function isIllegalUrlError(string $rawOutputFirstLine): bool
    {
        return $rawOutputFirstLine === 'java.lang.IllegalArgumentException: protocol = http host = null';
    }

    private static function isSslExceptionOutput(string $rawOutputFirstLine): bool
    {
        $signature = 'javax.net.ssl.SSLException';

        return substr($rawOutputFirstLine, 0, strlen($signature)) == $signature;
    }

    private static function isHttpAuthProtocolExceptionOutput(string $rawOutputFirstLine): bool
    {
        return preg_match('/java\.net\.ProtocolException: (Basic|Digest)/', $rawOutputFirstLine) > 0;
    }

    private static function isUnknownFileExceptionOutput(string $rawOutputFirstLine): bool
    {
        return preg_match('/java.lang.Exception: Unknown file/', $rawOutputFirstLine) > 0;
    }
}
