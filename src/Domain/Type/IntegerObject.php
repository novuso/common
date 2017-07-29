<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Type;

use Novuso\System\Exception\DomainException;
use Novuso\System\Type\Comparable;
use Novuso\System\Utility\Validate;

/**
 * IntegerObject is a wrapper for an integer
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class IntegerObject extends ValueObject implements Comparable
{
    /**
     * Integer value
     *
     * @var int
     */
    protected $value;

    /**
     * Constructs IntegerObject
     *
     * @param int $value The integer value
     */
    public function __construct(int $value)
    {
        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     */
    public static function fromString(string $value): IntegerObject
    {
        if (!is_numeric($value)) {
            $message = sprintf('Invalid integer value: %s', $value);
            throw new DomainException($message);
        }

        return new static((int) $value);
    }

    /**
     * Creates instance
     *
     * @param int $value The int value
     *
     * @return IntegerObject
     */
    public static function create(int $value): IntegerObject
    {
        return new static($value);
    }

    /**
     * Retrieves the integer value
     *
     * @return int
     */
    public function value(): int
    {
        return $this->value;
    }

    /**
     * Checks if equal to zero
     *
     * @return bool
     */
    public function isZero(): bool
    {
        return $this->value === 0;
    }

    /**
     * Checks if greater than zero
     *
     * @return bool
     */
    public function isPositive(): bool
    {
        return $this->value > 0;
    }

    /**
     * Checks if lesser than zero
     *
     * @return bool
     */
    public function isNegative(): bool
    {
        return $this->value < 0;
    }

    /**
     * Checks if even
     *
     * @return bool
     */
    public function isEven(): bool
    {
        return $this->value % 2 === 0;
    }

    /**
     * Checks if odd
     *
     * @return bool
     */
    public function isOdd(): bool
    {
        return $this->value % 2 !== 0;
    }

    /**
     * Creates an integer that represents the absolute value
     *
     * @return IntegerObject
     */
    public function abs(): IntegerObject
    {
        return new static((int) abs($this->value));
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        return (string) $this->value;
    }

    /**
     * Retrieves a binary representation
     *
     * @return string
     */
    public function toBinary(): string
    {
        return decbin($this->value);
    }

    /**
     * Retrieves a hexadecimal representation
     *
     * @return string
     */
    public function toHexadecimal(): string
    {
        return dechex($this->value);
    }

    /**
     * Retrieves an octal representation
     *
     * @return string
     */
    public function toOctal(): string
    {
        return decoct($this->value);
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

        return $this->value <=> $object->value;
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

        return $this->value === $object->value;
    }
}
