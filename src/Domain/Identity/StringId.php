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
     * String ID
     *
     * @var string
     */
    protected $id;

    /**
     * Constructs StringId
     *
     * @param string $id The ID string
     *
     * @throws DomainException When the ID is not valid
     */
    public function __construct(string $id)
    {
        $this->guardId($id);
        $this->id = $id;
    }

    /**
     * {@inheritdoc}
     */
    public static function fromString(string $value)
    {
        return new static($value);
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        return $this->id;
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

        $strComp = strnatcmp($this->id, $object->id);

        /** @var int $comp */
        $comp = $strComp <=> 0;

        return $comp;
    }

    /**
     * Validates the ID
     *
     * Override to implement validation.
     *
     * @codeCoverageIgnore
     *
     * @param string $id The ID string
     *
     * @return void
     *
     * @throws DomainException When the ID is not valid
     */
    protected function guardId(string $id): void
    {
    }
}
