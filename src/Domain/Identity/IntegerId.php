<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Identity;

use Novuso\Common\Domain\Type\ValueObject;
use Novuso\System\Exception\DomainException;
use Novuso\System\Utility\Assert;

/**
 * Class IntegerId
 */
abstract class IntegerId extends ValueObject implements Identifier
{
    /**
     * Constructs IntegerId
     *
     * @throws DomainException When the ID is not valid
     */
    public function __construct(protected int $id)
    {
        $this->guardId($id);
    }

    /**
     * @inheritDoc
     */
    public static function fromString(string $value): static
    {
        if (!is_numeric($value)) {
            $message = sprintf('Invalid integer value: %s', $value);
            throw new DomainException($message);
        }

        return new static((int) $value);
    }

    /**
     * Creates instance from integer
     *
     * @throws DomainException When the ID is not valid
     */
    public static function fromInt(int $id): static
    {
        return new static($id);
    }

    /**
     * Retrieves an integer representation
     */
    public function toInt(): int
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function toString(): string
    {
        return (string) $this->id;
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

        return $this->id <=> $object->id;
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
    protected function guardId(int $id): void
    {
    }
}
