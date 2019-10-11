<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Messaging\Command;

use Novuso\Common\Domain\Messaging\Payload;
use Novuso\System\Exception\DomainException;

/**
 * Interface Command
 */
interface Command extends Payload
{
    /**
     * Creates instance from array representation
     *
     * @param array $data The array representation
     *
     * @return Command
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
