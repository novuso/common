<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Domain\Value\DateTime;

use Novuso\Common\Domain\Value\DateTime\Date;
use Novuso\Common\Domain\Value\DateTime\DateRange;
use Novuso\System\Exception\DomainException;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Domain\Value\DateTime\DateRange
 */
class DateRangeTest extends UnitTestCase
{
    public function test_that_start_returns_expected_value()
    {
        $range = '2018-12-01..2018-12-10{1}';
        $dateRange = DateRange::fromString($range);

        static::assertSame('2018-12-01', $dateRange->start()->toString());
    }

    public function test_that_end_returns_expected_value()
    {
        $range = '2018-12-01..2018-12-10{1}';
        $dateRange = DateRange::fromString($range);

        static::assertSame('2018-12-10', $dateRange->end()->toString());
    }

    public function test_that_step_returns_expected_value()
    {
        $range = '2018-12-01..2018-12-10{1}';
        $dateRange = DateRange::fromString($range);

        static::assertSame(1, $dateRange->step());
    }

    public function test_that_from_string_returns_expected_instance()
    {
        $range = '2018-12-01..2018-12-10{1}';
        $dateRange = DateRange::fromString($range);

        static::assertSame($range, $dateRange->toString());
    }

    public function test_that_from_iterations_returns_expected_instance_forward()
    {
        $dateRange = DateRange::fromIterations(Date::fromString('2018-12-01'), 1, 10);

        static::assertSame('2018-12-01..2018-12-10{1}', $dateRange->toString());
    }

    public function test_that_from_iterations_returns_expected_instance_backwards()
    {
        $dateRange = DateRange::fromIterations(Date::fromString('2018-12-10'), -1, 10);

        static::assertSame('2018-12-10..2018-12-01{-1}', $dateRange->toString());
    }

    public function test_that_contains_returns_true_when_date_is_in_range()
    {
        $dateRange = DateRange::fromString('2018-12-01..2018-12-10{2}');

        static::assertTrue($dateRange->contains(Date::fromString('2018-12-05')));
    }

    public function test_that_contains_returns_false_when_date_is_out_of_range()
    {
        $dateRange = DateRange::fromString('2018-12-01..2018-12-10{2}');

        static::assertFalse($dateRange->contains(Date::fromString('2018-12-06')));
    }

    public function test_that_date_range_works_with_positive_step()
    {
        $start = Date::fromString('2018-12-01');
        $end = Date::fromString('2018-12-10');

        $dateRange = DateRange::create($start, $end);
        $dates = [];
        foreach ($dateRange as $date) {
            $dates[] = $date->toString();
        }

        $expected = [
            '2018-12-01',
            '2018-12-02',
            '2018-12-03',
            '2018-12-04',
            '2018-12-05',
            '2018-12-06',
            '2018-12-07',
            '2018-12-08',
            '2018-12-09',
            '2018-12-10'
        ];

        static::assertSame($expected, $dates);
    }

    public function test_that_date_range_works_with_negative_step()
    {
        $start = Date::fromString('2018-12-10');
        $end = Date::fromString('2018-12-01');
        $step = -1;

        $dateRange = DateRange::create($start, $end, $step);
        $dates = [];
        foreach ($dateRange as $date) {
            $dates[] = $date->toString();
        }

        $expected = [
            '2018-12-10',
            '2018-12-09',
            '2018-12-08',
            '2018-12-07',
            '2018-12-06',
            '2018-12-05',
            '2018-12-04',
            '2018-12-03',
            '2018-12-02',
            '2018-12-01'
        ];

        static::assertSame($expected, $dates);
    }

    public function test_that_create_throws_exception_when_step_is_zero()
    {
        $this->expectException(DomainException::class);

        DateRange::create(Date::fromString('2018-12-01'), Date::fromString('2018-12-10'), 0);
    }

    public function test_that_create_throws_exception_when_end_date_is_before_start_date_positive_step()
    {
        $this->expectException(DomainException::class);

        DateRange::create(Date::fromString('2018-12-10'), Date::fromString('2018-12-01'), 1);
    }

    public function test_that_create_throws_exception_when_start_date_is_before_end_date_negative_step()
    {
        $this->expectException(DomainException::class);

        DateRange::create(Date::fromString('2018-12-01'), Date::fromString('2018-12-10'), -1);
    }

    public function test_that_from_iterations_throws_exception_when_step_is_zero()
    {
        $this->expectException(DomainException::class);

        DateRange::fromIterations(Date::fromString('2018-12-01'), 0, 10);
    }

    public function test_that_from_string_throws_exception_when_string_is_invalid()
    {
        $this->expectException(DomainException::class);

        DateRange::fromString('2018-12-01...2018-12-10{1}');
    }
}
