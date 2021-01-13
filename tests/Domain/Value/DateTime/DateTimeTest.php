<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Domain\Value\DateTime;

use DateTime as NativeDateTime;
use DateTimeImmutable;
use DateTimeZone;
use Novuso\Common\Domain\Value\DateTime\Date;
use Novuso\Common\Domain\Value\DateTime\DateTime;
use Novuso\Common\Domain\Value\DateTime\Time;
use Novuso\Common\Domain\Value\DateTime\WeekDay;
use Novuso\System\Exception\AssertionException;
use Novuso\System\Exception\DomainException;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Domain\Value\DateTime\DateTime
 */
class DateTimeTest extends UnitTestCase
{
    public function test_that_create_returns_expected_instance()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');

        static::assertSame('2015-06-20T16:30:06.000000[America/Chicago]', $dateTime->toString());
    }

    public function test_that_now_returns_expected_instance()
    {
        $native = new NativeDateTime('now', new DateTimeZone('America/Chicago'));
        $dateTime = DateTime::now('America/Chicago');

        static::assertSame($native->format('Y-m-d'), $dateTime->format('Y-m-d'));
    }

    public function test_that_from_format_returns_expected_instance()
    {
        $format = 'Y-m-d\TH:i:s';
        $string = '2015-06-20T16:30:06';
        $dateTime = DateTime::fromFormat($format, $string, 'America/Chicago');

        static::assertSame('2015-06-20T16:30:06.000000[America/Chicago]', $dateTime->toString());
    }

    public function test_that_from_native_returns_expected_instance()
    {
        $string = '2015-06-20T16:30:06';
        $native = NativeDateTime::createFromFormat('Y-m-d\TH:i:s', $string, new DateTimeZone('America/Chicago'));
        $dateTime = DateTime::fromNative($native);

        static::assertSame('2015-06-20T16:30:06.000000[America/Chicago]', $dateTime->toString());
    }

    public function test_that_from_timestamp_returns_expected_instance()
    {
        $dateTime = DateTime::fromTimestamp(1434835806, 'America/Chicago');

        static::assertSame('2015-06-20T16:30:06.000000[America/Chicago]', $dateTime->toString());
    }

    public function test_that_from_string_returns_expected_instance()
    {
        $dateTimeString = '2015-06-20T16:30:06.000000[America/Chicago]';
        $dateTime = DateTime::fromString($dateTimeString);

        static::assertSame($dateTimeString, $dateTime->toString());
    }

    public function test_that_with_date_returns_expected_instance()
    {
        $dateTime = DateTime::now();
        $dateTime = $dateTime->withDate(Date::create(2016, 1, 1));

        static::assertSame('2016-01-01', $dateTime->date()->toString());
    }

    public function test_that_with_year_returns_expected_instance()
    {
        $dateTime = DateTime::create(2016, 1, 1, 0, 0, 0, 0, 'UTC');
        $dateTime = $dateTime->withYear(2015);

        static::assertSame('2015-01-01T00:00:00.000000[UTC]', $dateTime->toString());
    }

    public function test_that_with_month_returns_expected_instance()
    {
        $dateTime = DateTime::create(2016, 1, 1, 0, 0, 0, 0, 'UTC');
        $dateTime = $dateTime->withMonth(12);

        static::assertSame('2016-12-01T00:00:00.000000[UTC]', $dateTime->toString());
    }

    public function test_that_with_day_returns_expected_instance()
    {
        $dateTime = DateTime::create(2016, 1, 1, 0, 0, 0, 0, 'UTC');
        $dateTime = $dateTime->withDay(31);

        static::assertSame('2016-01-31T00:00:00.000000[UTC]', $dateTime->toString());
    }

    public function test_that_with_time_returns_expected_instance()
    {
        $dateTime = DateTime::now();
        $dateTime = $dateTime->withTime(Time::create(12, 0, 0));

        static::assertSame('12:00:00.000000', $dateTime->time()->toString());
    }

    public function test_that_with_hour_returns_expected_instance()
    {
        $dateTime = DateTime::create(2016, 1, 1, 0, 0, 0, 0, 'UTC');
        $dateTime = $dateTime->withHour(12);

        static::assertSame('2016-01-01T12:00:00.000000[UTC]', $dateTime->toString());
    }

    public function test_that_with_minute_returns_expected_instance()
    {
        $dateTime = DateTime::create(2016, 1, 1, 0, 0, 0, 0, 'UTC');
        $dateTime = $dateTime->withMinute(30);

        static::assertSame('2016-01-01T00:30:00.000000[UTC]', $dateTime->toString());
    }

    public function test_that_with_second_returns_expected_instance()
    {
        $dateTime = DateTime::create(2016, 1, 1, 0, 0, 0, 0, 'UTC');
        $dateTime = $dateTime->withSecond(30);

        static::assertSame('2016-01-01T00:00:30.000000[UTC]', $dateTime->toString());
    }

    public function test_that_with_timezone_returns_expected_instance()
    {
        $dateTime = DateTime::create(2016, 1, 1, 0, 0, 0, 0, 'UTC');
        $dateTime = $dateTime->withTimezone('America/Chicago');

        static::assertSame('2016-01-01T00:00:00.000000[America/Chicago]', $dateTime->toString());
    }

    public function test_that_to_timezone_returns_expected_instance()
    {
        $dateTime = DateTime::create(2016, 1, 1, 0, 0, 0, 0, 'UTC');
        $dateTime = $dateTime->toTimezone(new DateTimeZone('America/Chicago'));

        static::assertSame('2015-12-31T18:00:00.000000[America/Chicago]', $dateTime->toString());
    }

    public function test_that_locale_format_returns_expected_value()
    {
        if (setlocale(LC_TIME, 'en_US.utf8') === 'en_US.utf8') {
            $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');
            static::assertSame('June', $dateTime->localeFormat('%B'));
            setlocale(LC_TIME, '');
        } else {
            setlocale(LC_TIME, '');
            $this->markTestSkipped('Unable to set locale to en_US.utf8');
        }
    }

    public function test_that_iso_8601_returns_expected_value()
    {
        $dateTimeString = '2015-06-20T16:30:06.000000[America/Chicago]';
        $dateTime = DateTime::fromString($dateTimeString);

        static::assertSame('2015-06-20T16:30:06-05:00', $dateTime->iso8601());
    }

    public function test_that_date_returns_expected_instance()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');

        static::assertSame('2015-06-20', $dateTime->date()->toString());
    }

    public function test_that_time_returns_expected_instance()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');

        static::assertSame('16:30:06.000000', $dateTime->time()->toString());
    }

    public function test_that_timezone_returns_expected_instance()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');

        static::assertSame('America/Chicago', $dateTime->timezone()->toString());
    }

    public function test_that_timezone_offset_returns_expected_value()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');

        static::assertSame(-18000, $dateTime->timezoneOffset());
    }

    public function test_that_year_returns_expected_value()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');

        static::assertSame(2015, $dateTime->year());
    }

    public function test_that_month_returns_expected_value()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');

        static::assertSame(6, $dateTime->month());
    }

    public function test_that_month_name_returns_expected_value()
    {
        if (setlocale(LC_TIME, 'en_US.utf8') === 'en_US.utf8') {
            $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');
            static::assertSame('June', $dateTime->monthName());
            setlocale(LC_TIME, '');
        } else {
            setlocale(LC_TIME, '');
            $this->markTestSkipped('Unable to set locale to en_US.utf8');
        }
    }

    public function test_that_month_abbr_returns_expected_value()
    {
        if (setlocale(LC_TIME, 'en_US.utf8') === 'en_US.utf8') {
            $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');
            static::assertSame('Jun', $dateTime->monthAbbreviation());
            setlocale(LC_TIME, '');
        } else {
            setlocale(LC_TIME, '');
            $this->markTestSkipped('Unable to set locale to en_US.utf8');
        }
    }

    public function test_that_day_returns_expected_value()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');

        static::assertSame(20, $dateTime->day());
    }

    public function test_that_hour_returns_expected_value()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');

        static::assertSame(16, $dateTime->hour());
    }

    public function test_that_minute_returns_expected_value()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');

        static::assertSame(30, $dateTime->minute());
    }

    public function test_that_second_returns_expected_value()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');

        static::assertSame(6, $dateTime->second());
    }

    public function test_that_week_day_returns_expected_value()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');

        static::assertTrue(WeekDay::SATURDAY()->equals($dateTime->weekDay()));
    }

    public function test_that_week_day_name_returns_expected_value()
    {
        if (setlocale(LC_TIME, 'en_US.utf8') === 'en_US.utf8') {
            $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');
            static::assertSame('Saturday', $dateTime->weekDayName());
            setlocale(LC_TIME, '');
        } else {
            setlocale(LC_TIME, '');
            $this->markTestSkipped('Unable to set locale to en_US.utf8');
        }
    }

    public function test_that_week_day_abbr_returns_expected_value()
    {
        if (setlocale(LC_TIME, 'en_US.utf8') === 'en_US.utf8') {
            $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');
            static::assertSame('Sat', $dateTime->weekDayAbbreviation());
            setlocale(LC_TIME, '');
        } else {
            setlocale(LC_TIME, '');
            $this->markTestSkipped('Unable to set locale to en_US.utf8');
        }
    }

    public function test_that_timestamp_returns_expected_value()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');

        static::assertSame(1434835806, $dateTime->timestamp());
    }

    public function test_that_day_of_year_returns_expected_value()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');

        static::assertSame(170, $dateTime->dayOfYear());
    }

    public function test_that_week_number_returns_expected_value()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');

        static::assertSame(25, $dateTime->weekNumber());
    }

    public function test_that_days_in_month_returns_expected_value()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');

        static::assertSame(30, $dateTime->daysInMonth());
    }

    public function test_that_is_leap_year_returns_true_when_in_leap_year()
    {
        $dateTime = DateTime::create(2016, 6, 20, 16, 30, 6, 0, 'America/Chicago');

        static::assertTrue($dateTime->isLeapYear());
    }

    public function test_that_is_leap_year_returns_false_when_not_in_leap_year()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');

        static::assertFalse($dateTime->isLeapYear());
    }

    public function test_that_is_daylight_savings_returns_false_when_in_standard_time()
    {
        $dateTime = DateTime::create(2015, 11, 20, 16, 30, 6, 0, 'America/Chicago');

        static::assertFalse($dateTime->isDaylightSavings());
    }

    public function test_that_is_daylight_savings_returns_true_when_in_daylight_savings()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');

        static::assertTrue($dateTime->isDaylightSavings());
    }

    public function test_that_is_before_returns_false_when_passed_earlier_date_time()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');
        $otherDateTime = DateTime::create(2014, 6, 20, 16, 30, 6, 0, 'America/Chicago');

        static::assertFalse($dateTime->isBefore($otherDateTime));
    }

    public function test_that_is_before_returns_true_when_passed_later_date_time()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');
        $otherDateTime = DateTime::create(2016, 6, 20, 16, 30, 6, 0, 'America/Chicago');

        static::assertTrue($dateTime->isBefore($otherDateTime));
    }

    public function test_that_is_same_returns_false_when_passed_different_date_time()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');
        $otherDateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'UTC');

        static::assertFalse($dateTime->isSame($otherDateTime));
    }

    public function test_that_is_same_returns_true_when_passed_same_date_time()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');
        $otherDateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');

        static::assertTrue($dateTime->isSame($otherDateTime));
    }

    public function test_that_is_after_returns_false_when_passed_earlier_date_time()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');
        $otherDateTime = DateTime::create(2016, 6, 20, 16, 30, 6, 0, 'America/Chicago');

        static::assertFalse($dateTime->isAfter($otherDateTime));
    }

    public function test_that_is_after_returns_true_when_passed_later_date_time()
    {
        $dateTime = DateTime::create(2016, 6, 20, 16, 30, 6, 0, 'America/Chicago');
        $otherDateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');

        static::assertTrue($dateTime->isAfter($otherDateTime));
    }

    public function test_that_is_same_or_before_returns_false_when_passed_earlier_date_time()
    {
        $dateTime = DateTime::create(2016, 6, 20, 16, 30, 6, 0, 'America/Chicago');
        $otherDateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');

        static::assertFalse($dateTime->isSameOrBefore($otherDateTime));
    }

    public function test_that_is_same_or_before_returns_true_when_passed_later_date_time()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');
        $otherDateTime = DateTime::create(2016, 6, 20, 16, 30, 6, 0, 'America/Chicago');

        static::assertTrue($dateTime->isSameOrBefore($otherDateTime));
    }

    public function test_that_is_same_or_after_returns_false_when_passed_later_date_time()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');
        $otherDateTime = DateTime::create(2016, 6, 20, 16, 30, 6, 0, 'America/Chicago');

        static::assertFalse($dateTime->isSameOrAfter($otherDateTime));
    }

    public function test_that_is_same_or_after_returns_true_when_passed_earlier_date_time()
    {
        $dateTime = DateTime::create(2016, 6, 20, 16, 30, 6, 0, 'America/Chicago');
        $otherDateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');

        static::assertTrue($dateTime->isSameOrAfter($otherDateTime));
    }

    public function test_that_is_between_returns_false_when_out_of_range()
    {
        $dateTime = DateTime::create(2016, 6, 20, 16, 30, 6, 0, 'America/Chicago');
        $startDateTime = DateTime::create(2017, 6, 20, 16, 30, 6, 0, 'America/Chicago');
        $endDateTime = DateTime::create(2018, 6, 20, 16, 30, 6, 0, 'America/Chicago');

        static::assertFalse($dateTime->isBetween($startDateTime, $endDateTime));
    }

    public function test_that_is_between_returns_true_when_in_range()
    {
        $dateTime = DateTime::create(2016, 6, 20, 16, 30, 6, 0, 'America/Chicago');
        $startDateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');
        $endDateTime = DateTime::create(2018, 6, 20, 16, 30, 6, 0, 'America/Chicago');

        static::assertTrue($dateTime->isBetween($startDateTime, $endDateTime));
    }

    public function test_that_modify_returns_expected_instance()
    {
        $dateTime1 = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');
        $dateTime2 = $dateTime1->modify('-2 years');

        static::assertSame('2013-06-20T16:30:06.000000[America/Chicago]', $dateTime2->toString());
    }

    public function test_that_modify_does_not_change_original_instance()
    {
        $dateTime1 = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');
        $dateTime2 = $dateTime1->modify('-2 years');

        static::assertSame('2015-06-20T16:30:06.000000[America/Chicago]', $dateTime1->toString());
    }

    public function test_that_to_native_returns_expected_instance()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');
        $native = $dateTime->toNative();

        static::assertTrue(
            '2015-06-20T16:30:06-05:00' === $native->format('Y-m-d\TH:i:sP')
            && $native instanceof NativeDateTime
        );
    }

    public function test_that_to_immutable_returns_expected_instance()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');
        $native = $dateTime->toImmutable();

        static::assertTrue(
            '2015-06-20T16:30:06-05:00' === $native->format('Y-m-d\TH:i:sP')
            && $native instanceof DateTimeImmutable
        );
    }

    public function test_that_compare_to_returns_zero_for_same_instance()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');

        static::assertSame(0, $dateTime->compareTo($dateTime));
    }

    public function test_that_compare_to_returns_zero_for_same_value()
    {
        $dateTime1 = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');
        $dateTime2 = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');

        static::assertSame(0, $dateTime1->compareTo($dateTime2));
    }

    public function test_that_compare_to_returns_one_for_greater_timestamp()
    {
        $dateTime1 = DateTime::create(2015, 6, 20, 16, 30, 7, 0, 'America/Chicago');
        $dateTime2 = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');

        static::assertSame(1, $dateTime1->compareTo($dateTime2));
    }

    public function test_that_compare_to_returns_neg_one_for_lesser_timestamp()
    {
        $dateTime1 = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');
        $dateTime2 = DateTime::create(2015, 6, 20, 16, 30, 7, 0, 'America/Chicago');

        static::assertSame(-1, $dateTime1->compareTo($dateTime2));
    }

    public function test_that_compare_to_returns_one_for_greater_microsecond()
    {
        $dateTime1 = DateTime::create(2015, 6, 20, 16, 30, 6, 1, 'America/Chicago');
        $dateTime2 = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');

        static::assertSame(1, $dateTime1->compareTo($dateTime2));
    }

    public function test_that_compare_to_returns_neg_one_for_lesser_microsecond()
    {
        $dateTime1 = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');
        $dateTime2 = DateTime::create(2015, 6, 20, 16, 30, 6, 1, 'America/Chicago');

        static::assertSame(-1, $dateTime1->compareTo($dateTime2));
    }

    public function test_that_compare_to_returns_one_for_greater_timezone_string_compare()
    {
        $dateTime1 = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Los_Angeles');
        $dateTime2 = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');

        static::assertSame(1, $dateTime1->compareTo($dateTime2));
    }

    public function test_that_compare_to_returns_neg_one_for_lesser_timezone_string_compare()
    {
        $dateTime1 = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');
        $dateTime2 = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Los_Angeles');

        static::assertSame(-1, $dateTime1->compareTo($dateTime2));
    }

    public function test_that_create_throws_exception_for_invalid_timezone()
    {
        $this->expectException(DomainException::class);

        DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Somewhere');
    }

    public function test_that_now_throws_exception_for_invalid_timezone()
    {
        $this->expectException(DomainException::class);

        DateTime::now('America/Somewhere');
    }

    public function test_that_from_format_throws_exception_for_invalid_timezone()
    {
        $this->expectException(DomainException::class);

        DateTime::fromFormat('Y-m-d H:i:s', '2015-06-20 16:30:06', 'America/Somewhere');
    }

    public function test_that_from_format_throws_exception_for_invalid_data()
    {
        $this->expectException(DomainException::class);

        DateTime::fromFormat('Y-m-d H:i:s', '2015-06-20 16:', 'America/Chicago');
    }

    public function test_that_from_timestamp_throws_exception_for_invalid_timezone()
    {
        $this->expectException(DomainException::class);

        DateTime::fromTimestamp(1434835806, 'America/Somewhere');
    }

    public function test_that_from_string_throws_exception_for_invalid_format()
    {
        $this->expectException(DomainException::class);

        DateTime::fromString('2015-06-20T16:30:06-05:00');
    }

    public function test_that_modify_throws_exception_for_invalid_modification()
    {
        $this->expectException(DomainException::class);

        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');
        $dateTime->modify('-1 billion years');
    }

    public function test_that_compare_to_throws_exception_for_invalid_argument()
    {
        $this->expectException(AssertionException::class);

        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 0, 'America/Chicago');
        $dateTime->compareTo('2015-06-20T16:30:06.000000[America/Chicago]');
    }

    public function test_that_is_between_throws_exception_when_start_date_is_after_end_date()
    {
        $this->expectException(DomainException::class);

        $dateTime = DateTime::create(2016, 6, 20, 16, 30, 6, 0, 'America/Chicago');
        $startDateTime = DateTime::create(2019, 6, 20, 16, 30, 6, 0, 'America/Chicago');
        $endDateTime = DateTime::create(2018, 6, 20, 16, 30, 6, 0, 'America/Chicago');
        $dateTime->isBetween($startDateTime, $endDateTime);
    }
}
