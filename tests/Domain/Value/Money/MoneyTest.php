<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Domain\Value\Money;

use Novuso\Common\Domain\Value\Money\Money;
use Novuso\System\Exception\AssertionException;
use Novuso\System\Exception\DomainException;
use Novuso\System\Exception\RangeException;
use Novuso\System\Exception\TypeException;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Domain\Value\Money\Money
 */
class MoneyTest extends UnitTestCase
{
    public function test_that_magic_constructor_returns_expected_instance()
    {
        /** @var Money $money */
        $money = Money::USD(1725);
        static::assertSame('$17.25', $money->format());
    }

    public function test_that_from_string_returns_expected_instance()
    {
        $string = 'USD:1725';
        $money = Money::fromString($string);
        static::assertSame($string, $money->toString());
    }

    public function test_that_from_string_returns_expected_negative()
    {
        $string = 'USD:-1725';
        $money = Money::fromString($string);
        static::assertSame($string, $money->toString());
    }

    public function test_that_is_zero_returns_true_when_amount_is_zero()
    {
        /** @var Money $money */
        $money = Money::EUR(0);
        static::assertTrue($money->isZero());
    }

    public function test_that_is_zero_returns_false_when_amount_is_not_zero()
    {
        /** @var Money $money */
        $money = Money::EUR(-1000);
        static::assertFalse($money->isZero());
    }

    public function test_that_is_positive_returns_true_when_amount_is_positive()
    {
        /** @var Money $money */
        $money = Money::EUR(1000);
        static::assertTrue($money->isPositive());
    }

    public function test_that_is_positive_returns_false_when_amount_is_negative()
    {
        /** @var Money $money */
        $money = Money::EUR(-1000);
        static::assertFalse($money->isPositive());
    }

    public function test_that_is_negative_returns_true_when_amount_is_negative()
    {
        /** @var Money $money */
        $money = Money::EUR(-1000);
        static::assertTrue($money->isNegative());
    }

    public function test_that_is_negative_returns_false_when_amount_is_positive()
    {
        /** @var Money $money */
        $money = Money::EUR(1000);
        static::assertFalse($money->isNegative());
    }

    public function test_that_with_amount_returns_expected_instance()
    {
        /** @var Money $money */
        $money = Money::USD(100);
        $money = $money->withAmount(1500);
        static::assertSame('USD:1500', $money->toString());
    }

    public function test_that_add_returns_expected_instance()
    {
        /** @var Money $balance */
        $balance = Money::USD(45603);
        /** @var Money $credit */
        $credit = Money::USD(1434078);
        $balance = $balance->add($credit);
        static::assertSame('$14,796.81', $balance->format());
    }

    public function test_that_subtract_returns_expected_instance()
    {
        /** @var Money $balance */
        $balance = Money::USD(45603);
        /** @var Money $debit */
        $debit = Money::USD(11500);
        $balance = $balance->subtract($debit);
        static::assertSame('$341.03', $balance->format());
    }

    public function test_that_multiply_returns_expected_instance()
    {
        /** @var Money $money */
        $money = Money::EUR(1535);
        $money = $money->multiply(5);
        static::assertSame('€76.75', $money->format());
    }

    public function test_that_allocate_correctly_allocates_money()
    {
        /** @var Money $money */
        $money = Money::USD(326501);
        $ratios = [0.1, 0.3, 0.15, 0.15, 0.15, 0.15];
        $shares = $money->allocate($ratios);
        $output = [];
        foreach ($shares as $share) {
            $output[] = $share->format();
        }
        $expected = '[$326.51, $979.50, $489.75, $489.75, $489.75, $489.75]';
        static::assertSame($expected, sprintf('[%s]', implode(', ', $output)));
    }

    public function test_that_split_evenly_divides_money()
    {
        /** @var Money $money */
        $money = Money::USD(326501);
        $shares = $money->split(4);
        $output = [];
        foreach ($shares as $share) {
            $output[] = $share->format();
        }
        $expected = '[$816.26, $816.25, $816.25, $816.25]';
        static::assertSame($expected, sprintf('[%s]', implode(', ', $output)));
    }

    public function test_that_compare_to_returns_zero_for_same_instance()
    {
        /** @var Money $money */
        $money = Money::USD(1725);
        static::assertSame(0, $money->compareTo($money));
    }

    public function test_that_compare_to_returns_zero_for_same_value()
    {
        /** @var Money $money1 */
        $money1 = Money::USD(1725);
        /** @var Money $money2 */
        $money2 = Money::USD(1725);
        static::assertSame(0, $money1->compareTo($money2));
    }

    public function test_that_compare_to_returns_one_for_greater_value()
    {
        /** @var Money $money1 */
        $money1 = Money::USD(1726);
        /** @var Money $money2 */
        $money2 = Money::USD(1725);
        static::assertSame(1, $money1->compareTo($money2));
    }

    public function test_that_compare_to_returns_neg_one_for_lesser_value()
    {
        /** @var Money $money1 */
        $money1 = Money::USD(1725);
        /** @var Money $money2 */
        $money2 = Money::USD(1726);
        static::assertSame(-1, $money1->compareTo($money2));
    }

    public function test_that_greater_than_returns_true_for_greater_value()
    {
        /** @var Money $money1 */
        $money1 = Money::USD(1726);
        /** @var Money $money2 */
        $money2 = Money::USD(1725);
        static::assertTrue($money1->greaterThan($money2));
    }

    public function test_that_greater_than_returns_false_for_lesser_value()
    {
        /** @var Money $money1 */
        $money1 = Money::USD(1725);
        /** @var Money $money2 */
        $money2 = Money::USD(1726);
        static::assertFalse($money1->greaterThan($money2));
    }

    public function test_that_greater_than_or_equal_returns_true_for_greater_value()
    {
        /** @var Money $money1 */
        $money1 = Money::USD(1726);
        /** @var Money $money2 */
        $money2 = Money::USD(1725);
        static::assertTrue($money1->greaterThanOrEqual($money2));
    }

    public function test_that_greater_than_or_equal_returns_true_for_equal_value()
    {
        /** @var Money $money1 */
        $money1 = Money::USD(1725);
        /** @var Money $money2 */
        $money2 = Money::USD(1725);
        static::assertTrue($money1->greaterThanOrEqual($money2));
    }

    public function test_that_greater_than_or_equal_returns_false_for_lesser_value()
    {
        /** @var Money $money1 */
        $money1 = Money::USD(1725);
        /** @var Money $money2 */
        $money2 = Money::USD(1726);
        static::assertFalse($money1->greaterThanOrEqual($money2));
    }

    public function test_that_less_than_returns_true_for_lesser_value()
    {
        /** @var Money $money1 */
        $money1 = Money::USD(1725);
        /** @var Money $money2 */
        $money2 = Money::USD(1726);
        static::assertTrue($money1->lessThan($money2));
    }

    public function test_that_less_than_returns_false_for_greater_value()
    {
        /** @var Money $money1 */
        $money1 = Money::USD(1726);
        /** @var Money $money2 */
        $money2 = Money::USD(1725);
        static::assertFalse($money1->lessThan($money2));
    }

    public function test_that_less_than_or_equal_returns_true_for_lesser_value()
    {
        /** @var Money $money1 */
        $money1 = Money::USD(1725);
        /** @var Money $money2 */
        $money2 = Money::USD(1726);
        static::assertTrue($money1->lessThanOrEqual($money2));
    }

    public function test_that_less_than_or_equal_returns_true_for_equal_value()
    {
        /** @var Money $money1 */
        $money1 = Money::USD(1725);
        /** @var Money $money2 */
        $money2 = Money::USD(1725);
        static::assertTrue($money1->lessThanOrEqual($money2));
    }

    public function test_that_less_than_or_equal_returns_false_for_greater_value()
    {
        /** @var Money $money1 */
        $money1 = Money::USD(1726);
        /** @var Money $money2 */
        $money2 = Money::USD(1725);
        static::assertFalse($money1->lessThanOrEqual($money2));
    }

    public function test_that_magic_constructor_throws_exception_for_invalid_type()
    {
        $this->expectException(TypeException::class);

        Money::USD('1725');
    }

    public function test_that_from_string_throws_exception_for_invalid_formatting()
    {
        $this->expectException(DomainException::class);

        Money::fromString('$17.25');
    }

    public function test_that_multiply_throws_exception_for_result_out_of_bounds()
    {
        $this->expectException(RangeException::class);

        /** @var Money $money */
        $money = Money::USD(PHP_INT_MAX);
        $money->multiply(1.5);
    }

    public function test_that_split_throws_exception_for_num_less_than_one()
    {
        $this->expectException(DomainException::class);

        /** @var Money $money */
        $money = Money::USD(1725);
        $money->split(0);
    }

    public function test_that_compare_to_throws_exception_for_different_currency()
    {
        $this->expectException(DomainException::class);

        /** @var Money $money1 */
        $money1 = Money::USD(1725);
        /** @var Money $money2 */
        $money2 = Money::EUR(1725);
        $money1->compareTo($money2);
    }

    public function test_that_compare_to_throws_exception_for_invalid_argument()
    {
        $this->expectException(AssertionException::class);

        /** @var Money $money */
        $money = Money::USD(1725);
        $money->compareTo(1725);
    }
}
