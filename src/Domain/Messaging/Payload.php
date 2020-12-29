<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Messaging;

use Novuso\System\Exception\DomainException;
use Novuso\System\Type\Arrayable;

/**
 * Interface Payload
 */
interface Payload extends Arrayable
{
    /**
     * Creates instance from array representation
     *
     * @throws DomainException When data is not valid
     */
    public static function fromArray(array $data): static;

    /**
     * Retrieves an array representation
     */
    public function toArray(): array;
}
