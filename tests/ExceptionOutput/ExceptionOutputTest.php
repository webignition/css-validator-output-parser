<?php

namespace webignition\Tests\CssValidatorOutput\ExceptionOutput;

use webignition\CssValidatorOutput\ExceptionOutput\ExceptionOutput;
use webignition\CssValidatorOutput\ExceptionOutput\Type\Type;
use webignition\CssValidatorOutput\ExceptionOutput\Type\Value;

class ExceptionOutputTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider createDataProvider
     *
     * @param Type $type
     * @param bool $expectedIsHttpError
     * @param bool $expectedIsHttpClientError
     * @param bool $expectedIsHttpServerError
     * @param int|null $expectedGetHttpStatusCode
     * @param bool $expectedIsCurlError
     * @param int|null $expectedGetCurlCode
     * @param bool $expectedIsHttp404
     * @param bool $expectedIsCurl6
     */
    public function testCreate(
        Type $type,
        $expectedIsHttpError,
        $expectedIsHttpClientError,
        $expectedIsHttpServerError,
        $expectedGetHttpStatusCode,
        $expectedIsCurlError,
        $expectedGetCurlCode,
        $expectedIsHttp404,
        $expectedIsCurl6
    ) {
        $exceptionOutput = new ExceptionOutput();
        $exceptionOutput->setType($type);

        $this->assertEquals($type, $exceptionOutput->getType());
        $this->assertEquals($expectedIsHttpError, $exceptionOutput->isHttpError());
        $this->assertEquals($expectedIsHttpClientError, $exceptionOutput->isHttpClientError());
        $this->assertEquals($expectedIsHttpServerError, $exceptionOutput->isHttpServerError());
        $this->assertEquals($expectedGetHttpStatusCode, $exceptionOutput->getHttpStatusCode());
        $this->assertEquals($expectedIsCurlError, $exceptionOutput->isCurlError());
        $this->assertEquals($expectedGetCurlCode, $exceptionOutput->getCurlCode());
        $this->assertEquals($expectedIsHttp404, $exceptionOutput->isHttp404());
        $this->assertEquals($expectedIsCurl6, $exceptionOutput->isCurl6());
    }

    /**
     * @return array
     */
    public function createDataProvider()
    {
        return [
            'HTTP 404' => [
                'type' => new Type('http404'),
                'expectedIsHttpError' => true,
                'expectedIsHttpClientError' => true,
                'expectedIsHttpServerError' => false,
                'expectedGetHttpStatusCode' => 404,
                'expectedIsCurlError' => false,
                'expectedGetCurlCode' => null,
                'expectedIsHttp404' => true,
                'expectedIsCurl6' => false,
            ],
            'HTTP 401' => [
                'type' => new Type('http401'),
                'expectedIsHttpError' => true,
                'expectedIsHttpClientError' => true,
                'expectedIsHttpServerError' => false,
                'expectedGetHttpStatusCode' => 401,
                'expectedIsCurlError' => false,
                'expectedGetCurlCode' => null,
                'expectedIsHttp404' => false,
                'expectedIsCurl6' => false,
            ],
            'CURL 3' => [
                'type' => new Type('curl3'),
                'expectedIsHttpError' => false,
                'expectedIsHttpClientError' => false,
                'expectedIsHttpServerError' => false,
                'expectedGetHttpStatusCode' => null,
                'expectedIsCurlError' => true,
                'expectedGetCurlCode' => 3,
                'expectedIsHttp404' => false,
                'expectedIsCurl6' => false,
            ],
            'CURL 6' => [
                'type' => new Type('curl6'),
                'expectedIsHttpError' => false,
                'expectedIsHttpClientError' => false,
                'expectedIsHttpServerError' => false,
                'expectedGetHttpStatusCode' => null,
                'expectedIsCurlError' => true,
                'expectedGetCurlCode' => 6,
                'expectedIsHttp404' => false,
                'expectedIsCurl6' => true,
            ],
            'ssl exception' => [
                'type' => new Type(Value::SSL_EXCEPTION),
                'expectedIsHttpError' => false,
                'expectedIsHttpClientError' => false,
                'expectedIsHttpServerError' => false,
                'expectedGetHttpStatusCode' => null,
                'expectedIsCurlError' => false,
                'expectedGetCurlCode' => null,
                'expectedIsHttp404' => false,
                'expectedIsCurl6' => false,
            ],
        ];
    }
}
