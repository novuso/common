<?php

declare(strict_types=1);

namespace Novuso\Common\Application\HttpClient\Message;

use Novuso\System\Exception\DomainException;
use Psr\Http\Message\StreamInterface;

/**
 * Interface StreamFactory
 */
interface StreamFactory
{
    /**
     * Creates a StreamInterface instance
     *
     * @param string|resource|null $body Content body
     *
     * @throws DomainException When the body is invalid
     */
    public function createStream(mixed $body = null): StreamInterface;
}
