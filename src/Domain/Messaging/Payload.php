<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Messaging;

use Novuso\System\Exception\DomainException;
use Novuso\System\Type\Arrayable;

/**
 * Payload is the interface for a message payload
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface Payload extends Arrayable
{
    /**
     * Creates instance from array representation
     *
     * @param array $data The array representation
     *
     * @return Payload
     *
     * @throws DomainException When data is not valid
     */
    public static function fromArray(array $data);

    /**
     * Retrieves an array representation
     *
     * @return array
     */
    public function toArray(): array;
}
