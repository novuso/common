<?php declare(strict_types=1);

namespace Novuso\Common\Application\HttpClient\Transport;

use Novuso\Common\Application\HttpClient\Exception\Exception;
use Novuso\Common\Application\HttpClient\Message\Promise;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Interface HttpClient
 */
interface HttpClient extends ClientInterface
{
    /**
     * Sends a request
     *
     * @param RequestInterface $request The request
     *
     * @return ResponseInterface
     *
     * @throws Exception When an error occurs
     */
    public function sendRequest(RequestInterface $request): ResponseInterface;

    /**
     * Sends a request asynchronously with options
     *
     * @param RequestInterface $request The request
     *
     * @return Promise
     */
    public function sendAsync(RequestInterface $request): Promise;
}
