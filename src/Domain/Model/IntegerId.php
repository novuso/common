<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Model;

use Novuso\System\Exception\DomainException;
use Novuso\Common\Domain\Model\Api\Identifier;
use Novuso\System\Utility\Validate;

/**
 * IntegerId is the base class for integer identifiers
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
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
     * Creates instance from integer
     *
     * @param int $id The ID integer
     *
     * @return IntegerId
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

        assert(
            Validate::areSameType($this, $object),
            sprintf('Comparison requires instance of %s', static::class)
        );

        /** @var int $comp */
        $comp = $this->id <=> $object->id;

        return $comp;
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
    protected function guardId(int $id)
    {
    }
}
