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
use Throwable;

/**
 * HttpService is an HTTP client service
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class HttpService implements HttpClient, MessageFactory, StreamFactory, UriFactory
{
    public const METHOD_HEAD = 'HEAD';
    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';
    public const METHOD_PUT = 'PUT';
    public const METHOD_PATCH = 'PATCH';
    public const METHOD_DELETE = 'DELETE';
    public const METHOD_PURGE = 'PURGE';
    public const METHOD_OPTIONS = 'OPTIONS';
    public const METHOD_TRACE = 'TRACE';
    public const METHOD_CONNECT = 'CONNECT';

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
     * Sends a GET request
     *
     * @param string|UriInterface $uri        Request URI
     * @param array               $parameters Query parameters
     * @param array               $headers    Request headers
     * @param string              $protocol   Protocol version
     *
     * @return ResponseInterface
     *
     * @throws Throwable When an error occurs
     */
    public function get(
        $uri,
        array $parameters = [],
        array $headers = [],
        string $protocol = '1.1'
    ): ResponseInterface {
        $promise = $this->getAsync($uri, $parameters, $headers, $protocol);
        $promise->wait();

        if ($promise->getState() === Promise::REJECTED) {
            throw $promise->getException();
        }

        return $promise->getResponse();
    }

    /**
     * Sends a POST request
     *
     * @param string|UriInterface $uri         Request URI
     * @param array               $parameters  Body parameters
     * @param ContentType|null    $contentType The content type
     * @param array               $headers     Request headers
     * @param string              $protocol    Protocol version
     *
     * @return ResponseInterface
     *
     * @throws Throwable When an error occurs
     */
    public function post(
        $uri,
        array $parameters = [],
        ?ContentType $contentType = null,
        array $headers = [],
        string $protocol = '1.1'
    ): ResponseInterface {
        $promise = $this->postAsync($uri, $parameters, $contentType, $headers, $protocol);
        $promise->wait();

        if ($promise->getState() === Promise::REJECTED) {
            throw $promise->getException();
        }

        return $promise->getResponse();
    }

    /**
     * Sends a PUT request
     *
     * @param string|UriInterface $uri         Request URI
     * @param array               $parameters  Body parameters
     * @param ContentType|null    $contentType The content type
     * @param array               $headers     Request headers
     * @param string              $protocol    Protocol version
     *
     * @return ResponseInterface
     *
     * @throws Throwable When an error occurs
     */
    public function put(
        $uri,
        array $parameters = [],
        ?ContentType $contentType = null,
        array $headers = [],
        string $protocol = '1.1'
    ): ResponseInterface {
        $promise = $this->putAsync($uri, $parameters, $contentType, $headers, $protocol);
        $promise->wait();

        if ($promise->getState() === Promise::REJECTED) {
            throw $promise->getException();
        }

        return $promise->getResponse();
    }

    /**
     * Sends a PATCH request
     *
     * @param string|UriInterface $uri         Request URI
     * @param array               $parameters  Body parameters
     * @param ContentType|null    $contentType The content type
     * @param array               $headers     Request headers
     * @param string              $protocol    Protocol version
     *
     * @return ResponseInterface
     *
     * @throws Throwable When an error occurs
     */
    public function patch(
        $uri,
        array $parameters = [],
        ?ContentType $contentType = null,
        array $headers = [],
        string $protocol = '1.1'
    ): ResponseInterface {
        $promise = $this->patchAsync($uri, $parameters, $contentType, $headers, $protocol);
        $promise->wait();

        if ($promise->getState() === Promise::REJECTED) {
            throw $promise->getException();
        }

        return $promise->getResponse();
    }

    /**
     * Sends a DELETE request
     *
     * @param string|UriInterface $uri         Request URI
     * @param array               $parameters  Body parameters
     * @param ContentType|null    $contentType The content type
     * @param array               $headers     Request headers
     * @param string              $protocol    Protocol version
     *
     * @return ResponseInterface
     *
     * @throws Throwable When an error occurs
     */
    public function delete(
        $uri,
        array $parameters = [],
        ?ContentType $contentType = null,
        array $headers = [],
        string $protocol = '1.1'
    ): ResponseInterface {
        $promise = $this->deleteAsync($uri, $parameters, $contentType, $headers, $protocol);
        $promise->wait();

        if ($promise->getState() === Promise::REJECTED) {
            throw $promise->getException();
        }

        return $promise->getResponse();
    }

    /**
     * Sends a GET request asynchronously
     *
     * @param string|UriInterface $uri        Request URI
     * @param array               $parameters Query parameters
     * @param array               $headers    Request headers
     * @param string              $protocol   Protocol version
     *
     * @return Promise
     */
    public function getAsync(
        $uri,
        array $parameters = [],
        array $headers = [],
        string $protocol = '1.1'
    ): Promise {
        $request = $this->createQueryRequest(
            static::METHOD_GET,
            $uri,
            $parameters,
            $headers,
            $protocol
        );

        return $this->sendAsync($request);
    }

    /**
     * Sends a POST request asynchronously
     *
     * @param string|UriInterface $uri         Request URI
     * @param array               $parameters  Body parameters
     * @param ContentType|null    $contentType The content type
     * @param array               $headers     Request headers
     * @param string              $protocol    Protocol version
     *
     * @return Promise
     */
    public function postAsync(
        $uri,
        array $parameters = [],
        ?ContentType $contentType = null,
        array $headers = [],
        string $protocol = '1.1'
    ): Promise {
        $request = $this->createBodyRequest(
            static::METHOD_POST,
            $uri,
            $parameters,
            $contentType,
            $headers,
            $protocol
        );

        return $this->sendAsync($request);
    }

    /**
     * Sends a PUT request asynchronously
     *
     * @param string|UriInterface $uri         Request URI
     * @param array               $parameters  Query parameters
     * @param ContentType|null    $contentType The content type
     * @param array               $headers     Request headers
     * @param string              $protocol    Protocol version
     *
     * @return Promise
     */
    public function putAsync(
        $uri,
        array $parameters = [],
        ?ContentType $contentType = null,
        array $headers = [],
        string $protocol = '1.1'
    ): Promise {
        $request = $this->createBodyRequest(
            static::METHOD_PUT,
            $uri,
            $parameters,
            $contentType,
            $headers,
            $protocol
        );

        return $this->sendAsync($request);
    }

    /**
     * Sends a PATCH request asynchronously
     *
     * @param string|UriInterface $uri         Request URI
     * @param array               $parameters  Query parameters
     * @param ContentType|null    $contentType The content type
     * @param array               $headers     Request headers
     * @param string              $protocol    Protocol version
     *
     * @return Promise
     */
    public function patchAsync(
        $uri,
        array $parameters = [],
        ?ContentType $contentType = null,
        array $headers = [],
        string $protocol = '1.1'
    ): Promise {
        $request = $this->createBodyRequest(
            static::METHOD_PATCH,
            $uri,
            $parameters,
            $contentType,
            $headers,
            $protocol
        );

        return $this->sendAsync($request);
    }

    /**
     * Sends a DELETE request asynchronously
     *
     * @param string|UriInterface $uri         Request URI
     * @param array               $parameters  Query parameters
     * @param ContentType|null    $contentType The content type
     * @param array               $headers     Request headers
     * @param string              $protocol    Protocol version
     *
     * @return Promise
     */
    public function deleteAsync(
        $uri,
        array $parameters = [],
        ?ContentType $contentType = null,
        array $headers = [],
        string $protocol = '1.1'
    ): Promise {
        $request = $this->createBodyRequest(
            static::METHOD_DELETE,
            $uri,
            $parameters,
            $contentType,
            $headers,
            $protocol
        );

        return $this->sendAsync($request);
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

    /**
     * Creates a query request
     *
     * @param string              $method     Request method
     * @param string|UriInterface $uri        Request URI
     * @param array               $parameters Query parameters
     * @param array               $headers    Request headers
     * @param string              $protocol   Protocol version
     *
     * @return RequestInterface
     */
    protected function createQueryRequest(
        string $method,
        $uri,
        array $parameters = [],
        array $headers = [],
        string $protocol = '1.1'
    ): RequestInterface {
        $uri = $this->createUri($uri);

        if (!empty($parameters)) {
            $queryString = http_build_query($parameters);
            if ($uri->getQuery() === '') {
                $uri = $uri->withQuery($queryString);
            } else {
                $uri = $uri->withQuery($uri->getQuery().'&'.$queryString);
            }
        }

        return $this->createRequest($method, $uri, $headers, null, $protocol);
    }

    /**
     * Creates a body request
     *
     * @param string              $method      Request method
     * @param string|UriInterface $uri         Request URI
     * @param array               $parameters  Body parameters
     * @param ContentType|null    $contentType The content type
     * @param array               $headers     Request headers
     * @param string              $protocol    Protocol version
     *
     * @return RequestInterface
     */
    protected function createBodyRequest(
        string $method,
        $uri,
        array $parameters = [],
        ?ContentType $contentType = null,
        array $headers = [],
        string $protocol = '1.1'
    ): RequestInterface {
        if ($contentType === null) {
            $contentType = ContentType::fromValue(ContentType::FORM);
        }

        $uri = $this->createUri($uri);

        $body = null;
        if (!empty($parameters)) {
            switch ($contentType->value()) {
                case ContentType::FORM:
                    $body = http_build_query($parameters);
                    break;
                case ContentType::JSON:
                    $body = json_encode($parameters);
                    break;
                default:
                    break;
            }
        }

        if ($body !== null) {
            $headers = array_merge(['Content-Type' => $contentType->value()], $headers);
        }

        $body = $this->createStream($body);

        return $this->createRequest($method, $uri, $headers, $body, $protocol);
    }
}
