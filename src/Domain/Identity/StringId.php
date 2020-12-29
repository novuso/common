<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Identity;

use Novuso\Common\Domain\Type\ValueObject;
use Novuso\System\Exception\DomainException;
use Novuso\System\Utility\Assert;

/**
 * Class StringId
 */
abstract class StringId extends ValueObject implements Identifier
{
    /**
     * Constructs StringId
     *
     * @throws DomainException When the ID is not valid
     */
    public function __construct(protected string $id)
    {
        $this->guardId($id);
    }

    /**
     * @inheritDoc
     */
    public static function fromString(string $value): static
    {
        return new static($value);
    }

    /**
     * @inheritDoc
     */
    public function toString(): string
    {
        return $this->id;
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

        $strComp = strnatcmp($this->id, $object->id);

        return $strComp <=> 0;
    }

    /**
     * Validates the ID
     *
     * Override to implement validation.
     *
     * @codeCoverageIgnore
     *
     * @throws DomainException When the ID is not valid
     */
    protected function guardId(string $id): void
    {
    }
}
