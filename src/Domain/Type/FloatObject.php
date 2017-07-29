<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Type;

use Novuso\System\Exception\DomainException;
use Novuso\System\Type\Comparable;
use Novuso\System\Utility\Validate;

/**
 * FloatObject is a wrapper for a float
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class FloatObject extends ValueObject implements Comparable
{
    /**
     * Float value
     *
     * @var float
     */
    protected $value;

    /**
     * Constructs FloatObject
     *
     * @param float $value The float value
     */
    public function __construct(float $value)
    {
        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     */
    public static function fromString(string $value): FloatObject
    {
        if (!is_numeric($value)) {
            $message = sprintf('Invalid float value: %s', $value);
            throw new DomainException($message);
        }

        return new static((float) $value);
    }

    /**
     * Creates instance
     *
     * @param float $value The float value
     *
     * @return FloatObject
     */
    public static function create(float $value): FloatObject
    {
        return new static($value);
    }

    /**
     * Retrieves the float value
     *
     * @return float
     */
    public function value(): float
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
        return $this->value === 0.0;
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
     * Creates a float that represents the absolute value
     *
     * @return FloatObject
     */
    public function abs(): FloatObject
    {
        return new static((float) abs($this->value));
    }

    /**
     * Creates a float that represents the value rounded up
     *
     * @return FloatObject
     */
    public function ceil(): FloatObject
    {
        return new static((float) ceil($this->value));
    }

    /**
     * Creates a float that represents the value rounded down
     *
     * @return FloatObject
     */
    public function floor(): FloatObject
    {
        return new static((float) floor($this->value));
    }

    /**
     * Creates a float that represents the rounded value
     *
     * @param int               $precision    The number of digits to round to
     * @param RoundingMode|null $roundingMode The rounding mode
     *
     * @return FloatObject
     */
    public function round(int $precision = 0, ?RoundingMode $roundingMode = null): FloatObject
    {
        if ($roundingMode === null) {
            $roundingMode = RoundingMode::HALF_UP();
        }

        return new static((float) round($this->value, $precision, $roundingMode->value()));
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        return (string) $this->value;
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
