<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Identification;

use Novuso\Common\Domain\Type\ValueObject;
use Novuso\System\Exception\DomainException;
use Novuso\System\Utility\Validate;

/**
 * StringId is the base class for string identifiers
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
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
     * Creates instance from string
     *
     * @param string $id The ID string
     *
     * @return StringId
     */
    public static function fromString(string $id)
    {
        return new static($id);
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

        assert(
            Validate::areSameType($this, $object),
            sprintf('Comparison requires instance of %s', static::class)
        );

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
