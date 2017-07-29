<?php declare(strict_types=1);

namespace Novuso\Common\Application\HttpClient\Message;

use Novuso\System\Exception\DomainException;
use Psr\Http\Message\UriInterface;

/**
 * UriFactoryInterface is the interface for a URI factory
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface UriFactoryInterface
{
    /**
     * Creates a UriInterface instance
     *
     * @param mixed $uri The URI
     *
     * @return UriInterface
     *
     * @throws DomainException When the URI is invalid
     */
    public function createUri($uri): UriInterface;
}
