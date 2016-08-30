<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Model;

use Novuso\Common\Domain\Model\Api\Identifier;
use Novuso\Common\Domain\Model\Api\IdentifierFactory;
use Novuso\Common\Domain\Model\Identifier\Uuid;
use Novuso\System\Exception\DomainException;
use Novuso\System\Utility\Validate;

/**
 * UniqueId is the base class for UUID based identifiers
 *
 * @copyright Copyright (c) 2016, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
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
     * {@inheritdoc}
     */
    public static function generate()
    {
        return new static(Uuid::comb());
    }

    /**
     * Creates instance from a string representation
     *
     * @param string $value The UUID string
     *
     * @return UniqueId
     *
     * @throws DomainException When the UUID is not valid
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

        assert(
            Validate::areSameType($this, $object),
            sprintf('Comparison requires instance of %s', static::class)
        );

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
