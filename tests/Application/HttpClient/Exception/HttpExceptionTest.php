<?php declare(strict_types=1);

namespace Novuso\Common\Test\Application\HttpClient\Exception;

use Mockery\MockInterface;
use Novuso\Common\Application\HttpClient\Exception\HttpException;
use Novuso\System\Test\TestCase\UnitTestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @covers \Novuso\Common\Application\HttpClient\Exception\HttpException
 */
class HttpExceptionTest extends UnitTestCase
{
    public function test_that_create_returns_expected_instance()
    {
        $message = "[url]:https://www.google.com ";
        $message .= "[http method]:GET ";
        $message .= "[status code]:418 ";
        $message .= "[reason phrase]:I'm a teapot";

        /** @var RequestInterface|MockInterface $request */
        $request = $this->mock(RequestInterface::class);
        $request
            ->shouldReceive('getRequestTarget')
            ->andReturn('https://www.google.com');
        $request
            ->shouldReceive('getMethod')
            ->andReturn('GET');

        /** @var ResponseInterface|MockInterface $response */
        $response = $this->mock(ResponseInterface::class);
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
        /** @var RequestInterface|MockInterface $request */
        $request = $this->mock(RequestInterface::class);
        $request
            ->shouldReceive('getRequestTarget')
            ->andReturn('https://www.google.com');
        $request
            ->shouldReceive('getMethod')
            ->andReturn('GET');

        /** @var ResponseInterface|MockInterface $response */
        $response = $this->mock(ResponseInterface::class);
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
