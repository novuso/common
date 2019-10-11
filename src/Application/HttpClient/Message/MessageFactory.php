<?php declare(strict_types=1);

namespace Novuso\Common\Application\HttpClient\Message;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

/**
 * Interface MessageFactory
 */
interface MessageFactory
{
    /**
     * Creates a RequestInterface instance
     *
     * @param string                               $method   Request method
     * @param string|UriInterface                  $uri      Request URI
     * @param array                                $headers  Request headers
     * @param string|resource|StreamInterface|null $body     Request body
     * @param string                               $protocol Protocol version
     *
     * @return RequestInterface
     */
    public function createRequest(
        string $method,
        $uri,
        array $headers = [],
        $body = null,
        string $protocol = '1.1'
    ): RequestInterface;

    /**
     * Creates a ResponseInterface instance
     *
     * @param int                                  $status   Status code
     * @param array                                $headers  Response headers
     * @param string|resource|StreamInterface|null $body     Response body
     * @param string                               $protocol Protocol version
     * @param string|null                          $reason   Reason phrase
     *
     * @return ResponseInterface
     */
    public function createResponse(
        int $status = 200,
        array $headers = [],
        $body = null,
        string $protocol = '1.1',
        ?string $reason = null
    ): ResponseInterface;
}
