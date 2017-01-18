<?php declare(strict_types=1);

namespace Novuso\Common\Application\Http\Client;

use Psr\Http\Message\RequestInterface;

/**
 * HttpAsyncClient is the interface for an asynchronous HTTP client
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface HttpAsyncClient
{
    /**
     * Sends a request asynchronously
     *
     * @param RequestInterface $request The request
     * @param array            $options Request options
     *
     * @return Promise
     */
    public function sendAsync(RequestInterface $request, array $options = []): Promise;
}
