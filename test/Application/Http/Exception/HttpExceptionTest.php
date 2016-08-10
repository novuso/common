<?php

namespace Novuso\Test\Common\Application\Http\Exception;

use Novuso\Common\Application\Http\Exception\HttpException;
use Novuso\Test\System\TestCase\UnitTestCase;

/**
 * @covers Novuso\Common\Application\Http\Exception\HttpException
 */
class HttpExceptionTest extends UnitTestCase
{
    public function test_that_create_returns_expected_instance()
    {
        $message = "[url]:https://www.google.com ";
        $message .= "[http method]:GET ";
        $message .= "[status code]:418 ";
        $message .= "[reason phrase]:I'm a teapot";

        $request = $this->mock('Psr\\Http\\Message\\RequestInterface');
        $request
            ->shouldReceive('getRequestTarget')
            ->andReturn('https://www.google.com');
        $request
            ->shouldReceive('getMethod')
            ->andReturn('GET');

        $response = $this->mock('Psr\\Http\\Message\\ResponseInterface');
        $response
            ->shouldReceive('getStatusCode')
            ->andReturn(418);
        $response
            ->shouldReceive('getReasonPhrase')
            ->andReturn("I'm a teapot");

        $exception = HttpException::create($request, $response);
        $this->assertSame($message, $exception->getMessage());
    }

    public function test_that_get_response_returns_expected_instance()
    {
        $request = $this->mock('Psr\\Http\\Message\\RequestInterface');
        $request
            ->shouldReceive('getRequestTarget')
            ->andReturn('https://www.google.com');
        $request
            ->shouldReceive('getMethod')
            ->andReturn('GET');

        $response = $this->mock('Psr\\Http\\Message\\ResponseInterface');
        $response
            ->shouldReceive('getStatusCode')
            ->andReturn(418);
        $response
            ->shouldReceive('getReasonPhrase')
            ->andReturn("I'm a teapot");

        $exception = HttpException::create($request, $response);
        $this->assertSame($response, $exception->getResponse());
    }
}
