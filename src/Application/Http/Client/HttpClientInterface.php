<?php declare(strict_types=1);

namespace Novuso\Common\Application\Http\Client;

use Exception;
use Novuso\Common\Application\Http\Message\PromiseInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * HttpClientInterface is the interface for an HTTP client
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface HttpClientInterface
{
    /**
     * Sends a request
     *
     * @param RequestInterface $request The request
     * @param array            $options Request options
     *
     * @return ResponseInterface
     *
     * @throws Exception When an error occurs
     */
    public function send(RequestInterface $request, array $options = []): ResponseInterface;

    /**
     * Sends a request asynchronously
     *
     * @param RequestInterface $request The request
     * @param array            $options Request options
     *
     * @return PromiseInterface
     */
    public function sendAsync(RequestInterface $request, array $options = []): PromiseInterface;
}
