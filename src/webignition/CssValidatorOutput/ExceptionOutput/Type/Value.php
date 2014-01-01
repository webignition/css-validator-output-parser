<?php

namespace webignition\CssValidatorOutput\ExceptionOutput\Type;

class Value {
    
    
    /**
     * For exceptions that aren't any of the below
     */
    const UNKNOWN = 'unknown';
    
    /**
     * When validator attempts to retrieve a HTTP resource referenced by an 
     * invalid (malformed) URL
     */
    const ILLEGAL_URL = 'illegalurl';
    
    /**
     * When validator encounters a SSL-related issue trying to request a
     * resource over HTTPS
     */
    const SSL_EXCEPTION = 'sslexception';
    
    /**
     * When validator attempts to retrive a HTTP resource whereby the host in
     * the URL is not valid
     */
    const UNKNOWN_HOST = 'unknownhost';
    
    
    /**
     * When validator attempts to retrieve a local file that does not exist
     * or for which read permissions are not as they should be
     */
    const UNKNOWN_FILE = 'unknownfile';
    
    
    /**
     * When the validator encounters a content type that it is does not known
     * how to handle. Anything other than text/html or text/css will cause this
     */
    const UNKNOWN_MIME_TYPE = 'unknownmimetype';
    
}