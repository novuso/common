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
     * Integer ID
     *
     * @var int
     */
    protected $id;

    /**
     * Constructs IntegerId
     *
     * @param int $id The ID integer
     *
     * @throws DomainException When the ID is not valid
     */
    public function __construct(int $id)
    {
        $this->guardId($id);
        $this->id = $id;
    }

    /**
     * {@inheritdoc}
     */
    public static function fromString(string $value)
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
     * @param int $id The ID integer
     *
     * @return IntegerId
     *
     * @throws DomainException When the ID is not valid
     */
    public static function fromInt(int $id)
    {
        return new static($id);
    }

    /**
     * Retrieves an integer representation
     *
     * @return int
     */
    public function toInt(): int
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        return (string) $this->id;
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

        return $this->id <=> $object->id;
    }

    /**
     * Validates the ID
     *
     * Override to implement validation.
     *
     * @codeCoverageIgnore
     *
     * @param int $id The ID integer
     *
     * @return void
     *
     * @throws DomainException When the ID is not valid
     */
    protected function guardId(int $id): void
    {
    }
}
