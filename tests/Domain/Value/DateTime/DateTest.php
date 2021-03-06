<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Domain\Value\DateTime;

use DateTime as NativeDateTime;
use DateTimeZone;
use Novuso\Common\Domain\Value\DateTime\Date;
use Novuso\System\Exception\AssertionException;
use Novuso\System\Exception\DomainException;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Domain\Value\DateTime\Date
 */
class DateTest extends UnitTestCase
{
    public function test_that_create_returns_expected_instance()
    {
        $date = Date::create(2015, 6, 20);

        static::assertSame('2015-06-20', $date->toString());
    }

    public function test_that_now_returns_expected_instance()
    {
        $dateTime = new NativeDateTime('now', new DateTimeZone('America/Chicago'));
        $date = Date::now('America/Chicago');

        static::assertSame($dateTime->format('Y-m-d'), $date->toString());
    }

    public function test_that_from_native_returns_expected_instance()
    {
        $dateTime = new NativeDateTime('now', new DateTimeZone('America/Chicago'));
        $date = Date::fromNative($dateTime);

        static::assertSame($dateTime->format('Y-m-d'), $date->toString());
    }

    public function test_that_from_timestamp_returns_expected_instance()
    {
        $date = Date::fromTimestamp(1434835806, 'America/Chicago');

        static::assertSame('2015-06-20', $date->toString());
    }

    public function test_that_from_string_returns_expected_instance()
    {
        $dateString = '2015-06-20';
        $date = Date::fromString($dateString);

        static::assertSame($dateString, $date->toString());
    }

    public function test_that_with_year_returns_expected_instance()
    {
        $date = Date::fromString('2015-06-20')->withYear(2021);

        static::assertSame('2021-06-20', $date->toString());
    }

    public function test_that_with_month_returns_expected_instance()
    {
        $date = Date::fromString('2015-06-20')->withMonth(12);

        static::assertSame('2015-12-20', $date->toString());
    }

    public function test_that_with_day_returns_expected_instance()
    {
        $date = Date::fromString('2015-06-20')->withDay(1);

        static::assertSame('2015-06-01', $date->toString());
    }

    public function test_that_it_is_json_encodable()
    {
        $dateString = '2015-06-20';
        $date = Date::fromString($dateString);
        $data = ['date' => $date];

        static::assertSame('{"date":"2015-06-20"}', json_encode($data));
    }

    public function test_that_year_returns_expected_value()
    {
        $date = Date::create(2015, 6, 20);

        static::assertSame(2015, $date->year());
    }

    public function test_that_month_returns_expected_value()
    {
        $date = Date::create(2015, 6, 20);

        static::assertSame(6, $date->month());
    }

    public function test_that_day_returns_expected_value()
    {
        $date = Date::create(2015, 6, 20);

        static::assertSame(20, $date->day());
    }

    public function test_that_week_day_returns_expected_value()
    {
        $date = Date::create(2015, 6, 20);

        static::assertSame(6, $date->weekDay()->value());
    }

    public function test_that_week_day_name_returns_expected_value()
    {
        $date = Date::create(2015, 6, 20);

        static::assertSame('Saturday', $date->weekDayName());
    }

    public function test_that_is_before_returns_false_when_passed_earlier_date()
    {
        $date = Date::create(2015, 6, 20);
        $otherDate = Date::create(2014, 6, 20);

        static::assertFalse($date->isBefore($otherDate));
    }

    public function test_that_is_before_returns_true_when_passed_later_date()
    {
        $date = Date::create(2015, 6, 20);
        $otherDate = Date::create(2016, 6, 20);

        static::assertTrue($date->isBefore($otherDate));
    }

    public function test_that_is_same_returns_false_when_passed_different_date()
    {
        $date = Date::create(2015, 6, 20);
        $otherDate = Date::create(2014, 6, 20);

        static::assertFalse($date->isSame($otherDate));
    }

    public function test_that_is_same_returns_true_when_passed_same_date()
    {
        $date = Date::create(2015, 6, 20);
        $otherDate = Date::create(2015, 6, 20);

        static::assertTrue($date->isSame($otherDate));
    }

    public function test_that_is_after_returns_false_when_passed_earlier_date()
    {
        $date = Date::create(2015, 6, 20);
        $otherDate = Date::create(2016, 6, 20);

        static::assertFalse($date->isAfter($otherDate));
    }

    public function test_that_is_after_returns_true_when_passed_later_date()
    {
        $date = Date::create(2016, 6, 20);
        $otherDate = Date::create(2015, 6, 20);

        static::assertTrue($date->isAfter($otherDate));
    }

    public function test_that_is_same_or_before_returns_false_when_passed_earlier_date()
    {
        $date = Date::create(2016, 6, 20);
        $otherDate = Date::create(2015, 6, 20);

        static::assertFalse($date->isSameOrBefore($otherDate));
    }

    public function test_that_is_same_or_before_returns_true_when_passed_later_date()
    {
        $date = Date::create(2015, 6, 20);
        $otherDate = Date::create(2016, 6, 20);

        static::assertTrue($date->isSameOrBefore($otherDate));
    }

    public function test_that_is_same_or_after_returns_false_when_passed_later_date()
    {
        $date = Date::create(2015, 6, 20);
        $otherDate = Date::create(2016, 6, 20);

        static::assertFalse($date->isSameOrAfter($otherDate));
    }

    public function test_that_is_same_or_after_returns_true_when_passed_earlier_date()
    {
        $date = Date::create(2016, 6, 20);
        $otherDate = Date::create(2015, 6, 20);

        static::assertTrue($date->isSameOrAfter($otherDate));
    }

    public function test_that_is_between_returns_false_when_out_of_range()
    {
        $date = Date::create(2016, 6, 20);
        $startDate = Date::create(2017, 6, 20);
        $endDate = Date::create(2018, 6, 20);

        static::assertFalse($date->isBetween($startDate, $endDate));
    }

    public function test_that_is_between_returns_true_when_in_range()
    {
        $date = Date::create(2016, 6, 20);
        $startDate = Date::create(2015, 6, 20);
        $endDate = Date::create(2018, 6, 20);

        static::assertTrue($date->isBetween($startDate, $endDate));
    }

    public function test_that_compare_to_returns_zero_for_same_instance()
    {
        $date = Date::create(2015, 6, 20);

        static::assertSame(0, $date->compareTo($date));
    }

    public function test_that_compare_to_returns_zero_for_same_value()
    {
        $date1 = Date::create(2015, 6, 20);
        $date2 = Date::create(2015, 6, 20);

        static::assertSame(0, $date1->compareTo($date2));
    }

    public function test_that_compare_to_returns_one_for_greater_year()
    {
        $date1 = Date::create(2016, 6, 20);
        $date2 = Date::create(2015, 6, 20);

        static::assertSame(1, $date1->compareTo($date2));
    }

    public function test_that_compare_to_returns_neg_one_for_lesser_year()
    {
        $date1 = Date::create(2015, 6, 20);
        $date2 = Date::create(2016, 6, 20);

        static::assertSame(-1, $date1->compareTo($date2));
    }

    public function test_that_compare_to_returns_one_for_greater_month()
    {
        $date1 = Date::create(2015, 7, 20);
        $date2 = Date::create(2015, 6, 20);

        static::assertSame(1, $date1->compareTo($date2));
    }

    public function test_that_compare_to_returns_neg_one_for_lesser_month()
    {
        $date1 = Date::create(2015, 6, 20);
        $date2 = Date::create(2015, 7, 20);

        static::assertSame(-1, $date1->compareTo($date2));
    }

    public function test_that_compare_to_returns_one_for_greater_day()
    {
        $date1 = Date::create(2015, 6, 21);
        $date2 = Date::create(2015, 6, 20);

        static::assertSame(1, $date1->compareTo($date2));
    }

    public function test_that_compare_to_returns_neg_one_for_lesser_day()
    {
        $date1 = Date::create(2015, 6, 20);
        $date2 = Date::create(2015, 6, 21);

        static::assertSame(-1, $date1->compareTo($date2));
    }

    public function test_that_create_throws_exception_for_invalid_date()
    {
        $this->expectException(DomainException::class);

        Date::create(2015, 2, 30);
    }

    public function test_that_from_string_throws_exception_for_invalid_format()
    {
        $this->expectException(DomainException::class);

        Date::fromString('06-20-2015');
    }

    public function test_that_compare_to_throws_exception_for_invalid_argument()
    {
        $this->expectException(AssertionException::class);

        $date = Date::create(2015, 6, 20);
        $date->compareTo('2015-06-20');
    }

    public function test_that_is_between_throws_exception_when_start_date_is_after_end_date()
    {
        $this->expectException(DomainException::class);

        $dateTime = Date::create(2016, 6, 20);
        $startDateTime = Date::create(2019, 6, 20);
        $endDateTime = Date::create(2018, 6, 20);

        $dateTime->isBetween($startDateTime, $endDateTime);
    }
}
