<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Value\Money;

use Novuso\Common\Domain\Type\RoundingMode;
use Novuso\Common\Domain\Type\ValueObject;
use Novuso\System\Exception\AssertionException;
use Novuso\System\Exception\DomainException;
use Novuso\System\Exception\RangeException;
use Novuso\System\Exception\TypeException;
use Novuso\System\Type\Comparable;
use Novuso\System\Utility\Assert;

/**
 * Class Money
 */
final class Money extends ValueObject implements Comparable
{
    /**
     * Constructs Money
     */
    public function __construct(protected int $amount, protected Currency $currency) {}

    /**
     * Creates instance from a currency name and amount
     *
     * Maps static method `Money::USD(1725)` where `USD` is a class constant of
     * `Currency` and `1725` is the number of minor-units (eg. cents) in the
     * given currency. The monetary value for this example would be $17.25 USD.
     *
     * @throws DomainException When the currency code is invalid
     * @throws TypeException When the first arg is not an integer
     */
    public static function __callStatic(string $method, array $args): static
    {
        if (!isset($args[0]) || !is_int($args[0])) {
            $message = sprintf(
                '%s expects an integer amount, expressed in the smallest unit of currency',
                __METHOD__
            );
            throw new TypeException($message);
        }

        $amount = $args[0];
        $currency = Currency::fromValue($method);

        return new static($amount, $currency);
    }

    /**
     * @inheritDoc
     */
    public static function fromString(string $value): static
    {
        $pattern = '/\A(?P<code>[A-Z]{3}):(?P<amount>-?[\d]+)\z/';
        if (!preg_match($pattern, $value, $matches)) {
            $message = sprintf(
                '%s expects $value in "CUR:0000" format',
                __METHOD__
            );
            throw new DomainException($message);
        }

        $amount = (int) $matches['amount'];
        $currency = Currency::fromValue($matches['code']);

        return new static($amount, $currency);
    }

    /**
     * Creates instance with a given amount using the same currency
     */
    public function withAmount(int $amount): static
    {
        return new static($amount, $this->currency);
    }

    /**
     * Checks if the amount is zero
     */
    public function isZero(): bool
    {
        return $this->amount === 0;
    }

    /**
     * Checks if the amount is positive
     */
    public function isPositive(): bool
    {
        return $this->amount > 0;
    }

    /**
     * Checks if the amount is negative
     */
    public function isNegative(): bool
    {
        return $this->amount < 0;
    }

    /**
     * Retrieves the amount
     */
    public function amount(): int
    {
        return $this->amount;
    }

    /**
     * Retrieves the currency
     */
    public function currency(): Currency
    {
        return $this->currency;
    }

    /**
     * Creates instance that adds the given monetary value
     *
     * @throws DomainException When other has different currency
     * @throws RangeException When integer overflow occurs
     */
    public function add(Money $other): static
    {
        $this->guardCurrency($other);

        $amount = $this->amount + $other->amount;

        $this->guardAmountInBounds($amount);

        return $this->withAmount($amount);
    }

    /**
     * Creates instance that subtracts the given monetary value
     *
     * @throws DomainException When other has different currency
     * @throws RangeException When integer overflow occurs
     */
    public function subtract(Money $other): static
    {
        $this->guardCurrency($other);

        $amount = $this->amount - $other->amount;

        $this->guardAmountInBounds($amount);

        return $this->withAmount($amount);
    }

    /**
     * Creates instance that multiplies this value by the given value
     *
     * @throws RangeException When integer overflow occurs
     */
    public function multiply(int|float $multiplier, ?RoundingMode $mode = null): static
    {
        if ($mode === null) {
            $mode = RoundingMode::HALF_UP();
        }

        $amount = round($this->amount * $multiplier, 0, $mode->value());
        $amount = $this->castToInteger($amount);

        return $this->withAmount($amount);
    }

    /**
     * Allocates the money according to a list of ratios
     *
     * @throws RangeException When integer overflow occurs
     */
    public function allocate(array $ratios): array
    {
        $total = array_sum($ratios);
        $remainder = $this->amount;
        $shares = [];

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
     * @throws DomainException When num is not greater than zero
     * @throws RangeException When integer overflow occurs
     */
    public function split(int $number): array
    {
        if ($number <= 0) {
            $message = sprintf(
                '%s expects $num to be greater than zero',
                __METHOD__
            );
            throw new DomainException($message);
        }

        $amount = $this->castToInteger($this->amount / $number);
        $remainder = $this->amount % $number;
        $shares = [];

        for ($i = 0; $i < $number; $i++) {
            $shares[] = $this->withAmount($amount);
        }

        for ($i = 0; $i < $remainder; $i++) {
            $shares[$i] = $this->withAmount($shares[$i]->amount + 1);
        }

        return $shares;
    }

    /**
     * Retrieves a formatted string representation
     */
    public function format(string $locale = 'en_US'): string
    {
        return LocaleFormatter::fromLocale($locale)->format($this);
    }

    /**
     * @inheritDoc
     */
    public function toString(): string
    {
        return sprintf('%s:%d', $this->currency->code(), $this->amount);
    }

    /**
     * Checks whether other money has the same currency
     */
    public function isSameCurrency(Money $other): bool
    {
        return $this->currency->equals($other->currency);
    }

    /**
     * @inheritDoc
     */
    public function compareTo($object): int
    {
        if ($this === $object) {
            return 0;
        }

        Assert::areSameType($this, $object);

        $this->guardCurrency($object);

        return $this->amount <=> $object->amount;
    }

    /**
     * Checks if this monetary value is greater than another
     *
     * @throws AssertionException When the currencies do not match
     */
    public function greaterThan(Money $other): bool
    {
        return $this->compareTo($other) === 1;
    }

    /**
     * Checks if this monetary value is greater or equal to another
     *
     * @throws AssertionException When the currencies do not match
     */
    public function greaterThanOrEqual(Money $other): bool
    {
        return $this->compareTo($other) >= 0;
    }

    /**
     * Checks if this monetary value is less than another
     *
     * @throws AssertionException When the currencies do not match
     */
    public function lessThan(Money $other): bool
    {
        return $this->compareTo($other) === -1;
    }

    /**
     * Checks if this monetary value is less or equal to another
     *
     * @throws AssertionException When the currencies do not match
     */
    public function lessThanOrEqual(Money $other): bool
    {
        return $this->compareTo($other) <= 0;
    }

    /**
     * Casts amount to an integer after math operation; checking boundaries
     *
     * @throws RangeException When integer overflow occurs
     */
    protected function castToInteger(int|float $amount): int
    {
        $this->guardAmountInBounds($amount);

        return (int) $amount;
    }

    /**
     * Validates amount did not overflow integer bounds
     *
     * @throws RangeException When integer overflow occurs
     */
    protected function guardAmountInBounds(int|float $amount): void
    {
        if (abs($amount) > PHP_INT_MAX) {
            throw new RangeException('Integer overflow');
        }
    }

    /**
     * Validates another monetary value uses the same currency
     *
     * @throws AssertionException When other has different currency
     */
    protected function guardCurrency(Money $other): void
    {
        if (!$this->isSameCurrency($other)) {
            throw new AssertionException(
                'Math and comparison operations require the same currency'
            );
        }
    }
}
