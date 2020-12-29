<?php declare(strict_types=1);

namespace Novuso\Common\Application\HttpClient;

use Novuso\Common\Application\HttpClient\Message\MessageFactory;
use Novuso\Common\Application\HttpClient\Message\Promise;
use Novuso\Common\Application\HttpClient\Message\StreamFactory;
use Novuso\Common\Application\HttpClient\Message\UriFactory;
use Novuso\Common\Application\HttpClient\Transport\HttpClient;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

/**
 * Class HttpService
 */
final class HttpService implements HttpClient, MessageFactory, StreamFactory, UriFactory
{
    /**
     * Constructs HttpService
     */
    public function __construct(
        protected HttpClient $httpClient,
        protected MessageFactory $messageFactory,
        protected StreamFactory $streamFactory,
        protected UriFactory $uriFactory
    ) {}

    /**
     * @inheritDoc
     */
    public function send(RequestInterface $request, array $options = []): ResponseInterface
    {
        return $this->httpClient->send($request, $options);
    }

    /**
     * @inheritDoc
     */
    public function sendAsync(RequestInterface $request, array $options = []): Promise
    {
        return $this->httpClient->sendAsync($request, $options);
    }

    /**
     * @inheritDoc
     */
    public function createRequest(
        string $method,
        UriInterface|string $uri,
        array $headers = [],
        StreamInterface|string|null $body = null,
        string $protocol = '1.1'
    ): RequestInterface {
        return $this->messageFactory->createRequest(
            $method,
            $uri,
            $headers,
            $body,
            $protocol
        );
    }

    /**
     * @inheritDoc
     */
    public function createResponse(
        int $status = 200,
        array $headers = [],
        StreamInterface|string|null $body = null,
        string $protocol = '1.1',
        ?string $reason = null
    ): ResponseInterface {
        return $this->messageFactory->createResponse(
            $status,
            $headers,
            $body,
            $protocol,
            $reason
        );
    }

    /**
     * @inheritDoc
     */
    public function createStream(mixed $body = null): StreamInterface
    {
        return $this->streamFactory->createStream($body);
    }

    /**
     * @inheritDoc
     */
    public function createUri(string $uri): UriInterface
    {
        return $this->uriFactory->createUri($uri);
    }
}
