<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Model\Money;

use Novuso\Common\Domain\Model\ValueObject;
use Novuso\System\Exception\DomainException;
use Novuso\System\Exception\RangeException;
use Novuso\System\Exception\TypeException;
use Novuso\System\Type\Comparable;
use Novuso\System\Utility\Test;
use Novuso\System\Utility\VarPrinter;

/**
 * Money represents a monetary value
 *
 * @copyright Copyright (c) 2016, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class Money extends ValueObject implements Comparable
{
    /**
     * Monetary amount
     *
     * Amount in the currency sub-unit
     *
     * @var int
     */
    protected $amount;

    /**
     * Currency
     *
     * @var Currency
     */
    protected $currency;

    /**
     * Constructs Money
     *
     * @param int      $amount   The monetary amount
     * @param Currency $currency The currency
     */
    public function __construct(int $amount, Currency $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    /**
     * Creates instance from a currency name and amount
     *
     * Maps static method `Money::USD(1725)` where `USD` is a class constant of
     * `Currency` and `1725` is the number of minor-units (eg. cents) in the
     * given currency. The monetary value for this example would be $17.25 USD.
     *
     * @param string $method The name of the method
     * @param array  $args   A list of arguments
     *
     * @return Money
     *
     * @throws DomainException When the currency code is invalid
     * @throws TypeException When the first arg is not an integer
     */
    public static function __callStatic($method, array $args): Money
    {
        if (!isset($args[0]) || !is_int($args[0])) {
            $message = sprintf(
                '%s expects an integer amount, expressed in the smallest unit of currency',
                __METHOD__
            );
            throw new TypeException($message);
        }

        /** @var int $amount */
        $amount = $args[0];
        /** @var Currency $currency */
        $currency = Currency::fromValue($method);

        return new static($amount, $currency);
    }

    /**
     * Creates instance from a string representation
     *
     * @param string $value The money string
     *
     * @return Money
     *
     * @throws DomainException When the money string is not valid
     */
    public static function fromString(string $value): Money
    {
        $pattern = '/\A(?P<code>[A-Z]{3}):(?P<amount>-?[\d]+)\z/';
        if (!preg_match($pattern, $value, $matches)) {
            $message = sprintf('%s expects $value in "CUR:0000" format', __METHOD__);
            throw new DomainException($message);
        }

        /** @var int $amount */
        $amount = (int) $matches['amount'];
        /** @var Currency $currency */
        $currency = Currency::fromValue($matches['code']);

        return new static($amount, $currency);
    }

    /**
     * Creates instance with a given amount using the same currency
     *
     * @param int $amount The amount
     *
     * @return Money
     */
    public function withAmount(int $amount): Money
    {
        return new static($amount, $this->currency);
    }

    /**
     * Checks if the amount is zero
     *
     * @return bool
     */
    public function isZero(): bool
    {
        return $this->amount === 0;
    }

    /**
     * Checks if the amount is positive
     *
     * @return bool
     */
    public function isPositive(): bool
    {
        return $this->amount > 0;
    }

    /**
     * Checks if the amount is negative
     *
     * @return bool
     */
    public function isNegative(): bool
    {
        return $this->amount < 0;
    }

    /**
     * Retrieves the amount
     *
     * @return int
     */
    public function amount(): int
    {
        return $this->amount;
    }

    /**
     * Retrieves the currency
     *
     * @return Currency
     */
    public function currency(): Currency
    {
        return $this->currency;
    }

    /**
     * Creates instance that adds the given monetary value
     *
     * @param Money $other The other monetary value
     *
     * @return Money
     *
     * @throws DomainException When other has different currency
     * @throws RangeException When integer overflow occurs
     */
    public function add(Money $other): Money
    {
        $this->guardCurrency($other);

        $amount = $this->amount + $other->amount;

        $this->guardAmountInBounds($amount);

        return $this->withAmount($amount);
    }

    /**
     * Creates instance that subtracts the given monetary value
     *
     * @param Money $other The other monetary value
     *
     * @return Money
     *
     * @throws DomainException When other has different currency
     * @throws RangeException When integer overflow occurs
     */
    public function subtract(Money $other): Money
    {
        $this->guardCurrency($other);

        $amount = $this->amount - $other->amount;

        $this->guardAmountInBounds($amount);

        return $this->withAmount($amount);
    }

    /**
     * Creates instance that multiplies this value by the given value
     *
     * @param int|float         $multiplier The multiplier
     * @param RoundingMode|null $mode       The rounding mode; null for HALF_UP
     *
     * @return Money
     *
     * @throws DomainException When multiplier is not an int or float
     * @throws RangeException When integer overflow occurs
     */
    public function multiply($multiplier, RoundingMode $mode = null): Money
    {
        if ($mode === null) {
            $mode = RoundingMode::HALF_UP();
        }

        $this->guardOperand($multiplier);

        $amount = round($this->amount * $multiplier, 0, $mode->value());
        $amount = $this->castToInteger($amount);

        return $this->withAmount($amount);
    }

    /**
     * Creates instance that divides this value by the given value
     *
     * @param int|float         $divisor The divisor
     * @param RoundingMode|null $mode    The rounding mode; null for HALF_UP
     *
     * @return Money
     *
     * @throws DomainException When divisor is not an int or float
     * @throws DomainException When the divisor is zero
     * @throws RangeException When integer overflow occurs
     */
    public function divide($divisor, RoundingMode $mode = null): Money
    {
        if ($mode === null) {
            $mode = RoundingMode::HALF_UP();
        }

        $this->guardOperand($divisor);

        if ($divisor === 0 || $divisor === 0.0) {
            throw new DomainException('Division by zero');
        }

        $amount = round($this->amount / $divisor, 0, $mode->value());
        $amount = $this->castToInteger($amount);

        return $this->withAmount($amount);
    }

    /**
     * Allocates the money according to a list of ratios
     *
     * @param array $ratios The list of ratios
     *
     * @return Money[]
     */
    public function allocate(array $ratios): array
    {
        $total = array_sum($ratios);
        $remainder = $this->amount;

        foreach ($ratios as $ratio) {
            $amount = $this->castToInteger($this->amount * $ratio / $total);
            $shares[] = $this->withAmount($amount);
            $remainder -= $amount;
        }

        for ($i = 0; $i < $remainder; $i++) {
            $shares[$i] = $this->withAmount($shares[$i]->amount + 1);
        }

        return $shares;
    }

    /**
     * Allocates the money among a given number of targets
     *
     * @param int $num The number of targets
     *
     * @return Money[]
     *
     * @throws DomainException When num is not greater than zero
     */
    public function split(int $num): array
    {
        if ($num <= 0) {
            $message = sprintf('%s expects $num to be greater than zero', __METHOD__);
            throw new DomainException($message);
        }

        $amount = $this->castToInteger($this->amount / $num);
        $remainder = $this->amount % $num;

        for ($i = 0; $i < $num; $i++) {
            $shares[] = $this->withAmount($amount);
        }

        for ($i = 0; $i < $remainder; $i++) {
            $shares[$i] = $this->withAmount($shares[$i]->amount + 1);
        }

        return $shares;
    }

    /**
     * Retrieves a formatted string representation
     *
     * @param string $locale The locale
     *
     * @return string
     */
    public function format(string $locale = 'en_US'): string
    {
        return LocaleFormatter::fromLocale($locale)->format($this);
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        return sprintf('%s:%d', $this->currency->code(), $this->amount);
    }

    /**
     * Checks whether other money has the same currency
     *
     * @param Money $other The other monetary value
     *
     * @return bool
     */
    public function isSameCurrency(Money $other): bool
    {
        return $this->currency->equals($other->currency);
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
            Test::areSameType($this, $object),
            sprintf('Comparison requires instance of %s', static::class)
        );

        $this->guardCurrency($object);

        /** @var int $comp */
        $comp = $this->amount <=> $object->amount;

        return $comp;
    }

    /**
     * Checks if this monetary value is greater than another
     *
     * @param Money $other The other monetary value
     *
     * @return bool
     */
    public function greaterThan(Money $other): bool
    {
        return $this->compareTo($other) === 1;
    }

    /**
     * Checks if this monetary value is greater or equal to another
     *
     * @param Money $other The other monetary value
     *
     * @return bool
     */
    public function greaterThanOrEqual(Money $other): bool
    {
        return $this->compareTo($other) >= 0;
    }

    /**
     * Checks if this monetary value is less than another
     *
     * @param Money $other The other monetary value
     *
     * @return bool
     */
    public function lessThan(Money $other): bool
    {
        return $this->compareTo($other) === -1;
    }

    /**
     * Checks if this monetary value is less or equal to another
     *
     * @param Money $other The other monetary value
     *
     * @return bool
     */
    public function lessThanOrEqual(Money $other): bool
    {
        return $this->compareTo($other) <= 0;
    }

    /**
     * Casts amount to an integer after math operation; checking boundaries
     *
     * @param mixed $amount The amount
     *
     * @return int
     *
     * @throws RangeException When integer overflow occurs
     */
    protected function castToInteger($amount): int
    {
        $this->guardAmountInBounds($amount);

        return (int) $amount;
    }

    /**
     * Validates amount did not overflow integer bounds
     *
     * @param mixed $amount The amount
     *
     * @return void
     *
     * @throws RangeException When integer overflow occurs
     */
    protected function guardAmountInBounds($amount)
    {
        if (abs($amount) > PHP_INT_MAX) {
            throw new RangeException('Integer overflow');
        }
    }

    /**
     * Validates monetary operand is an integer or float
     *
     * @param mixed $operand The operand
     *
     * @return void
     *
     * @throws DomainException When operand is not an int or float
     */
    protected function guardOperand($operand)
    {
        if (!is_int($operand) && !is_float($operand)) {
            $message = sprintf(
                'Operand must be an integer or float; received (%s) %s',
                gettype($operand),
                VarPrinter::toString($operand)
            );
            throw new DomainException($message);
        }
    }

    /**
     * Validates another monetary value uses the same currency
     *
     * @param Money $other The other monetary value
     *
     * @return void
     *
     * @throws DomainException When other has different currency
     */
    protected function guardCurrency(Money $other)
    {
        if (!$this->isSameCurrency($other)) {
            throw new DomainException('Math and comparison operations require the same currency');
        }
    }
}
