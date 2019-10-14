<?php declare(strict_types=1);

namespace Novuso\Common\Test\Application\HttpClient;

use Mockery\MockInterface;
use Novuso\Common\Application\HttpClient\HttpService;
use Novuso\Common\Application\HttpClient\Message\MessageFactory;
use Novuso\Common\Application\HttpClient\Message\Promise;
use Novuso\Common\Application\HttpClient\Message\StreamFactory;
use Novuso\Common\Application\HttpClient\Message\UriFactory;
use Novuso\Common\Application\HttpClient\Transport\HttpClient;
use Novuso\Common\Application\HttpFoundation\HttpMethod;
use Novuso\System\Test\TestCase\UnitTestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

/**
 * @covers \Novuso\Common\Application\HttpClient\HttpService
 */
class HttpServiceTest extends UnitTestCase
{
    /** @var HttpService */
    protected $httpService;
    /** @var HttpClient|MockInterface */
    protected $mockHttpClient;
    /** @var MessageFactory|MockInterface */
    protected $mockMessageFactory;
    /** @var StreamFactory|MockInterface */
    protected $mockStreamFactory;
    /** @var UriFactory|MockInterface */
    protected $mockUriFactory;

    protected function setUp(): void
    {
        $this->mockHttpClient = $this->mock(HttpClient::class);
        $this->mockMessageFactory = $this->mock(MessageFactory::class);
        $this->mockStreamFactory = $this->mock(StreamFactory::class);
        $this->mockUriFactory = $this->mock(UriFactory::class);
        $this->httpService = new HttpService(
            $this->mockHttpClient,
            $this->mockMessageFactory,
            $this->mockStreamFactory,
            $this->mockUriFactory
        );
    }

    public function test_that_send_async_delegates_to_http_client()
    {
        $mockPromise = $this->mock(Promise::class);
        /** @var RequestInterface|MockInterface $mockRequest */
        $mockRequest = $this->mock(RequestInterface::class);

        $this->mockHttpClient
            ->shouldReceive('sendAsync')
            ->once()
            ->with($mockRequest)
            ->andReturn($mockPromise);

        $this->assertSame($mockPromise, $this->httpService->sendAsync($mockRequest));
    }

    public function test_that_send_request_delegates_to_http_client()
    {
        $mockResponse = $this->mock(ResponseInterface::class);
        /** @var RequestInterface|MockInterface $mockRequest */
        $mockRequest = $this->mock(RequestInterface::class);

        $this->mockHttpClient
            ->shouldReceive('sendRequest')
            ->once()
            ->with($mockRequest)
            ->andReturn($mockResponse);

        $this->assertSame($mockResponse, $this->httpService->sendRequest($mockRequest));
    }

    public function test_that_create_request_delegates_to_message_factory()
    {
        $mockRequest = $this->mock(RequestInterface::class);
        $method = HttpMethod::GET;
        $url = 'https://www.google.com';

        $this->mockMessageFactory
            ->shouldReceive('createRequest')
            ->once()
            ->with($method, $url, [], null, '1.1')
            ->andReturn($mockRequest);

        $this->assertSame($mockRequest, $this->httpService->createRequest($method, $url));
    }

    public function test_that_create_response_delegates_to_message_factory()
    {
        $mockResponse = $this->mock(ResponseInterface::class);

        $this->mockMessageFactory
            ->shouldReceive('createResponse')
            ->once()
            ->with(200, [], null, '1.1', null)
            ->andReturn($mockResponse);

        $this->assertSame($mockResponse, $this->httpService->createResponse());
    }

    public function test_that_create_stream_delegates_to_stream_factory()
    {
        $body = 'foo';
        $mockStream = $this->mock(StreamInterface::class);

        $this->mockStreamFactory
            ->shouldReceive('createStream')
            ->once()
            ->with($body)
            ->andReturn($mockStream);

        $this->assertSame($mockStream, $this->httpService->createStream($body));
    }

    public function test_that_create_uri_delegates_to_uri_factory()
    {
        $uri = 'https://www.google.com';
        $mockUri = $this->mock(UriInterface::class);

        $this->mockUriFactory
            ->shouldReceive('createUri')
            ->once()
            ->with($uri)
            ->andReturn($mockUri);

        $this->assertSame($mockUri, $this->httpService->createUri($uri));
    }
}
