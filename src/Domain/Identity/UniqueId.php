<?php

declare(strict_types=1);

namespace Novuso\Common\Domain\Identity;

use Novuso\Common\Domain\Type\ValueObject;
use Novuso\Common\Domain\Value\Identifier\Uuid;
use Novuso\System\Utility\Assert;
use Novuso\System\Utility\ClassName;
use Novuso\System\Utility\Validate;

/**
 * Class UniqueId
 */
abstract class UniqueId extends ValueObject implements Identifier, IdentifierFactory
{
    /**
     * Constructs UniqueId
     */
    public function __construct(protected Uuid $uuid)
    {
    }

    /**
     * Generates a unique identifier
     */
    public static function generate(): static
    {
        return new static(Uuid::comb());
    }

    /**
     * @inheritDoc
     */
    public static function fromString(string $value): static
    {
        return new static(Uuid::parse($value));
    }

    /**
     * @inheritDoc
     */
    public function toString(): string
    {
        return $this->uuid->toString();
    }

    /**
     * @inheritDoc
     */
    public function compareTo(mixed $object): int
    {
        if ($this === $object) {
            return 0;
        }

        Assert::areSameType($this, $object);

        return $this->uuid->compareTo($object->uuid);
    }

    /**
     * @inheritDoc
     */
    public function equals(mixed $object): bool
    {
        if ($this === $object) {
            return true;
        }

        if (!Validate::areSameType($this, $object)) {
            return false;
        }

        return $this->uuid->equals($object->uuid);
    }

    /**
     * @inheritDoc
     */
    public function hashValue(): string
    {
        return sprintf(
            '%s:%s',
            ClassName::canonical(static::class),
            $this->uuid->hashValue()
        );
    }
}
