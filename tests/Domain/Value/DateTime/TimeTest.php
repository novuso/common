<?php declare(strict_types=1);

namespace Novuso\Common\Test\Domain\Value\DateTime;

use DateTime as NativeDateTime;
use DateTimeZone;
use Novuso\Common\Domain\Value\DateTime\Time;
use Novuso\System\Exception\AssertionException;
use Novuso\System\Exception\DomainException;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Domain\Value\DateTime\Time
 */
class TimeTest extends UnitTestCase
{
    public function test_that_create_returns_expected_instance()
    {
        $time = Time::create(16, 30, 6, 10101);
        $this->assertSame('16:30:06.010101', $time->toString());
    }

    public function test_that_now_returns_expected_instance()
    {
        $dateTime = new NativeDateTime('now', new DateTimeZone('America/Chicago'));
        $time = Time::now('America/Chicago');
        $hour = (int) $dateTime->format('G');
        $this->assertSame($hour, $time->hour());
    }

    public function test_that_from_native_returns_expected_instance()
    {
        $string = '2015-06-20T16:30:06';
        $dateTime = NativeDateTime::createFromFormat('Y-m-d\TH:i:s', $string, new DateTimeZone('America/Chicago'));
        $time = Time::fromNative($dateTime);
        $this->assertSame('16:30:06.000000', $time->toString());
    }

    public function test_that_from_timestamp_returns_expected_instance()
    {
        $time = Time::fromTimestamp(1434835806, 'America/Chicago');
        $this->assertSame('16:30:06.000000', $time->toString());
    }

    public function test_that_from_string_returns_expected_instance()
    {
        $timeString = '16:30:06.000000';
        $time = Time::fromString($timeString);
        $this->assertSame($timeString, $time->toString());
    }

    public function test_that_hour_returns_expected_value()
    {
        $time = Time::create(16, 30, 6);
        $this->assertSame(16, $time->hour());
    }

    public function test_that_minute_returns_expected_value()
    {
        $time = Time::create(16, 30, 6);
        $this->assertSame(30, $time->minute());
    }

    public function test_that_second_returns_expected_value()
    {
        $time = Time::create(16, 30, 6);
        $this->assertSame(6, $time->second());
    }

    public function test_that_microsecond_returns_expected_value()
    {
        $time = Time::create(16, 30, 6, 23);
        $this->assertSame(23, $time->microsecond());
    }

    public function test_that_compare_to_returns_zero_for_same_instance()
    {
        $time = Time::create(16, 30, 6);
        $this->assertSame(0, $time->compareTo($time));
    }

    public function test_that_compare_to_returns_zero_for_same_value()
    {
        $time1 = Time::create(16, 30, 6);
        $time2 = Time::create(16, 30, 6);
        $this->assertSame(0, $time1->compareTo($time2));
    }

    public function test_that_compare_to_returns_one_for_greater_hour()
    {
        $time1 = Time::create(17, 30, 6);
        $time2 = Time::create(16, 30, 6);
        $this->assertSame(1, $time1->compareTo($time2));
    }

    public function test_that_compare_to_returns_neg_one_for_lesser_hour()
    {
        $time1 = Time::create(16, 30, 6);
        $time2 = Time::create(17, 30, 6);
        $this->assertSame(-1, $time1->compareTo($time2));
    }

    public function test_that_compare_to_returns_one_for_greater_minute()
    {
        $time1 = Time::create(16, 31, 6);
        $time2 = Time::create(16, 30, 6);
        $this->assertSame(1, $time1->compareTo($time2));
    }

    public function test_that_compare_to_returns_neg_one_for_lesser_minute()
    {
        $time1 = Time::create(16, 30, 6);
        $time2 = Time::create(16, 31, 6);
        $this->assertSame(-1, $time1->compareTo($time2));
    }

    public function test_that_compare_to_returns_one_for_greater_second()
    {
        $time1 = Time::create(16, 30, 7);
        $time2 = Time::create(16, 30, 6);
        $this->assertSame(1, $time1->compareTo($time2));
    }

    public function test_that_compare_to_returns_neg_one_for_lesser_second()
    {
        $time1 = Time::create(16, 30, 6);
        $time2 = Time::create(16, 30, 7);
        $this->assertSame(-1, $time1->compareTo($time2));
    }

    public function test_that_compare_to_returns_one_for_greater_microsecond()
    {
        $time1 = Time::create(16, 30, 6, 1);
        $time2 = Time::create(16, 30, 6, 0);
        $this->assertSame(1, $time1->compareTo($time2));
    }

    public function test_that_compare_to_returns_neg_one_for_lesser_microsecond()
    {
        $time1 = Time::create(16, 30, 6, 0);
        $time2 = Time::create(16, 30, 6, 1);
        $this->assertSame(-1, $time1->compareTo($time2));
    }

    public function test_that_create_throws_exception_for_hour_out_of_range()
    {
        $this->expectException(DomainException::class);

        Time::create(24, 30, 6);
    }

    public function test_that_create_throws_exception_for_minute_out_of_range()
    {
        $this->expectException(DomainException::class);

        Time::create(16, 74, 6);
    }

    public function test_that_create_throws_exception_for_second_out_of_range()
    {
        $this->expectException(DomainException::class);

        Time::create(16, 30, -6);
    }

    public function test_that_create_throws_exception_for_microsecond_out_of_range()
    {
        $this->expectException(DomainException::class);

        Time::create(16, 30, 6, 1000000);
    }

    public function test_that_from_string_throws_exception_for_invalid_format()
    {
        $this->expectException(DomainException::class);

        Time::fromString('16:30:06');
    }

    public function test_that_compare_to_throws_exception_for_invalid_argument()
    {
        $this->expectException(AssertionException::class);

        $time = Time::create(16, 30, 6);
        $time->compareTo('16:30:06');
    }
}
