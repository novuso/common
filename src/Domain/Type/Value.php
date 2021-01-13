<?php

declare(strict_types=1);

namespace Novuso\Common\Domain\Type;

use JsonSerializable;
use Novuso\System\Exception\DomainException;
use Novuso\System\Type\Equatable;

/**
 * Interface Value
 *
 * Implementations must adhere to value characteristics:
 *
 * * It is maintained as immutable
 * * It measures, quantifies, or describes a thing in the domain
 * * It models a conceptual whole by composing related attributes as a unit
 * * It is completely replaceable when the measurement or description changes
 * * It can be compared with others using value equality
 * * It supplies its collaborators with side-effect-free behavior
 */
interface Value extends Equatable, JsonSerializable
{
    /**
     * Creates instance from a string representation
     *
     * @throws DomainException When value is not valid
     */
    public static function fromString(string $value): static;

    /**
     * Retrieves a string representation
     */
    public function toString(): string;

    /**
     * Handles casting to a string
     */
    public function __toString(): string;
}
