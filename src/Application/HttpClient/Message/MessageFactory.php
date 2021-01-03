<?php

declare(strict_types=1);

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
     * @param string|StreamInterface|null $body The request body
     */
    public function createRequest(
        string $method,
        string|UriInterface $uri,
        array $headers = [],
        mixed $body = null,
        string $protocol = '1.1'
    ): RequestInterface;

    /**
     * Creates a ResponseInterface instance
     *
     * @param string|StreamInterface|null $body The response body
     */
    public function createResponse(
        int $status = 200,
        array $headers = [],
        mixed $body = null,
        string $protocol = '1.1',
        ?string $reason = null
    ): ResponseInterface;
}
