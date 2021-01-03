<?php

declare(strict_types=1);

namespace Novuso\Common\Application\HttpClient\Message;

use Novuso\System\Exception\DomainException;
use Psr\Http\Message\UriInterface;

/**
 * Interface UriFactory
 */
interface UriFactory
{
    /**
     * Creates a UriInterface instance
     *
     * @throws DomainException When the URI is invalid
     */
    public function createUri(string $uri): UriInterface;
}
