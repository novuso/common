<?php

declare(strict_types=1);

namespace Novuso\Common\Application\HttpClient\Transport;

use Novuso\Common\Application\HttpClient\Exception\Exception;
use Novuso\Common\Application\HttpClient\Message\Promise;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Interface HttpClient
 */
interface HttpClient
{
    /**
     * Sends a request
     *
     * @throws Exception When an error occurs
     */
    public function send(
        RequestInterface $request,
        array $options = []
    ): ResponseInterface;

    /**
     * Sends a request asynchronously with options
     */
    public function sendAsync(
        RequestInterface $request,
        array $options = []
    ): Promise;
}
