<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Identity;

use Novuso\Common\Domain\Type\ValueObject;
use Novuso\Common\Domain\Value\Identifier\Uuid;
use Novuso\System\Utility\Assert;
use Novuso\System\Utility\Validate;

/**
 * Class UniqueId
 */
abstract class UniqueId extends ValueObject implements Identifier, IdentifierFactory
{
    /**
     * UUID
     *
     * @var Uuid
     */
    protected $uuid;

    /**
     * Constructs UniqueId
     *
     * @param Uuid $uuid The Uuid instance
     */
    public function __construct(Uuid $uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * Generates a unique identifier
     *
     * @return static
     */
    public static function generate()
    {
        return new static(Uuid::comb());
    }

    /**
     * {@inheritdoc}
     */
    public static function fromString(string $value)
    {
        return new static(Uuid::parse($value));
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        return $this->uuid->toString();
    }

    /**
     * {@inheritdoc}
     */
    public function compareTo($object): int
    {
        if ($this === $object) {
            return 0;
        }

        Assert::areSameType($this, $object);

        return $this->uuid->compareTo($object->uuid);
    }

    /**
     * {@inheritdoc}
     */
    public function equals($object): bool
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
     * {@inheritdoc}
     */
    public function hashValue(): string
    {
        return $this->uuid->hashValue();
    }
}
