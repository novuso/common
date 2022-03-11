<?php

declare(strict_types=1);

namespace Novuso\Common\Domain\Type;

use Novuso\System\Utility\Validate;

/**
 * Class ValueObject
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
abstract class ValueObject implements Value
{
    /**
     * @inheritDoc
     */
    abstract public function toString(): string;

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        return $this->toString();
    }

    /**
     * @inheritDoc
     */
    public function equals($object): bool
    {
        if ($this === $object) {
            return true;
        }

        /** @var ValueObject $object */
        if (!Validate::areSameType($this, $object)) {
            return false;
        }

        return $this->toString() === $object->toString();
    }

    /**
     * @inheritDoc
     */
    public function hashValue(): string
    {
        return $this->toString();
    }
}
