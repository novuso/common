<?php declare(strict_types=1);

namespace Novuso\Common\Application\Http\Client;

use Exception;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * HttpClient is the interface for an HTTP client
 *
 * @copyright Copyright (c) 2016, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface HttpClient
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
}
