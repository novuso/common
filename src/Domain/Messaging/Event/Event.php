<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Messaging\Event;

use Novuso\Common\Domain\Messaging\Payload;
use Novuso\System\Exception\DomainException;

/**
 * Interface Event
 */
interface Event extends Payload
{
    /**
     * Creates instance from array representation
     *
     * @param array $data The array representation
     *
     * @return Event
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
