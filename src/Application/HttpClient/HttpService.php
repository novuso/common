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
     * HTTP client
     *
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * Message factory
     *
     * @var MessageFactory
     */
    protected $messageFactory;

    /**
     * Stream factory
     *
     * @var StreamFactory
     */
    protected $streamFactory;

    /**
     * URI factory
     *
     * @var UriFactory
     */
    protected $uriFactory;

    /**
     * Constructs HttpService
     *
     * @param HttpClient     $httpClient     The HTTP client
     * @param MessageFactory $messageFactory The message factory
     * @param StreamFactory  $streamFactory  The stream factory
     * @param UriFactory     $uriFactory     The URI factory
     */
    public function __construct(
        HttpClient $httpClient,
        MessageFactory $messageFactory,
        StreamFactory $streamFactory,
        UriFactory $uriFactory
    ) {
        $this->httpClient = $httpClient;
        $this->messageFactory = $messageFactory;
        $this->streamFactory = $streamFactory;
        $this->uriFactory = $uriFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function send(RequestInterface $request, array $options = []): ResponseInterface
    {
        return $this->httpClient->send($request, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function sendAsync(RequestInterface $request, array $options = []): Promise
    {
        return $this->httpClient->sendAsync($request, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        return $this->httpClient->sendRequest($request);
    }

    /**
     * {@inheritdoc}
     */
    public function createRequest(
        string $method,
        $uri,
        array $headers = [],
        $body = null,
        string $protocol = '1.1'
    ): RequestInterface {
        return $this->messageFactory->createRequest($method, $uri, $headers, $body, $protocol);
    }

    /**
     * {@inheritdoc}
     */
    public function createResponse(
        int $status = 200,
        array $headers = [],
        $body = null,
        string $protocol = '1.1',
        ?string $reason = null
    ): ResponseInterface {
        return $this->messageFactory->createResponse($status, $headers, $body, $protocol, $reason);
    }

    /**
     * {@inheritdoc}
     */
    public function createStream($body = null): StreamInterface
    {
        return $this->streamFactory->createStream($body);
    }

    /**
     * {@inheritdoc}
     */
    public function createUri($uri): UriInterface
    {
        return $this->uriFactory->createUri($uri);
    }
}
