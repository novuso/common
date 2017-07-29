<?php declare(strict_types=1);

namespace Novuso\Common\Application\HttpClient\Message;

use Novuso\System\Exception\DomainException;
use Psr\Http\Message\StreamInterface;

/**
 * StreamFactoryInterface is the interface for a stream factory
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface StreamFactoryInterface
{
    /**
     * Creates a StreamInterface instance
     *
     * @param string|resource|StreamInterface|null $body Content body
     *
     * @return StreamInterface
     *
     * @throws DomainException When the body is invalid
     */
    public function createStream($body = null): StreamInterface;
}
