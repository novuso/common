<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Aggregate;

use Novuso\Common\Domain\Identification\Identifier;
use Novuso\System\Type\Comparable;
use Novuso\System\Type\Equatable;
use function Novuso\Common\Functions\same_type;

/**
 * Entity is the base class for a domain entity
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
abstract class Entity implements Comparable, Equatable
{
    /**
     * Retrieves the ID
     *
     * @return Identifier
     */
    abstract public function id();

    /**
     * {@inheritdoc}
     */
    public function compareTo($object): int
    {
        if ($this === $object) {
            return 0;
        }

        assert(
            same_type($this, $object),
            sprintf('Comparison requires instance of %s', static::class)
        );

        return $this->id()->compareTo($object->id());
    }

    /**
     * {@inheritdoc}
     */
    public function equals($object): bool
    {
        if ($this === $object) {
            return true;
        }

        if (!same_type($this, $object)) {
            return false;
        }

        return $this->id()->equals($object->id());
    }

    /**
     * {@inheritdoc}
     */
    public function hashValue(): string
    {
        return $this->id()->hashValue();
    }
}
