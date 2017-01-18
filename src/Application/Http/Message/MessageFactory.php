<?php declare(strict_types=1);

namespace Novuso\Common\Application\Http\Message;

use Psr\Http\Message\UriInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * MessageFactory is the interface for a request/response factory
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
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
        string $reason = null
    ): ResponseInterface;
}
