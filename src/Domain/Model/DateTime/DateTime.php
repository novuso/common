<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Model\DateTime;

use DateTime as NativeDateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Novuso\Common\Domain\Model\ValueObject;
use Novuso\System\Exception\DomainException;
use Novuso\System\Type\Comparable;
use Novuso\System\Utility\Test;

/**
 * DateTime represents a specific date and time
 *
 * @copyright Copyright (c) 2016, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class DateTime extends ValueObject implements Comparable
{
    /**
     * String format
     *
     * @var string
     */
    const STRING_FORMAT = 'Y-m-d\TH:i:s';

    /**
     * Date
     *
     * @var Date
     */
    protected $date;

    /**
     * Time
     *
     * @var Time
     */
    protected $time;

    /**
     * Timezone
     *
     * @var Timezone
     */
    protected $timezone;

    /**
     * Native DateTime
     *
     * @var DateTimeImmutable|null
     */
    protected $dateTime;

    /**
     * Constructs DateTime
     *
     * @param Date     $date     The date
     * @param Time     $time     The time
     * @param Timezone $timezone The timezone
     */
    public function __construct(Date $date, Time $time, Timezone $timezone)
    {
        $this->date = $date;
        $this->time = $time;
        $this->timezone = $timezone;
    }

    /**
     * Creates instance from date and time values
     *
     * @param int         $year     The year
     * @param int         $month    The month
     * @param int         $day      The day
     * @param int         $hour     The hour
     * @param int         $minute   The minute
     * @param int         $second   The second
     * @param string|null $timezone The timezone string or null for default
     *
     * @return DateTime
     *
     * @throws DomainException When the date/time is not valid
     */
    public static function create(
        int $year,
        int $month,
        int $day,
        int $hour,
        int $minute,
        int $second,
        string $timezone = null
    ): DateTime {
        $timezone = $timezone ?: date_default_timezone_get();
        assert(Test::isTimezone($timezone), sprintf('Invalid timezone: %s', $timezone));

        return new static(
            Date::create($year, $month, $day),
            Time::create($hour, $minute, $second),
            Timezone::create($timezone)
        );
    }

    /**
     * Creates instance for the current date and time
     *
     * @param string|null $timezone The timezone string or null for default
     *
     * @return DateTime
     */
    public static function now(string $timezone = null): DateTime
    {
        $timezone = $timezone ?: date_default_timezone_get();
        assert(Test::isTimezone($timezone), sprintf('Invalid timezone: %s', $timezone));

        $dateTime = new DateTimeImmutable('now', new DateTimeZone($timezone));
        $year = (int) $dateTime->format('Y');
        $month = (int) $dateTime->format('n');
        $day = (int) $dateTime->format('j');
        $hour = (int) $dateTime->format('G');
        $minute = (int) $dateTime->format('i');
        $second = (int) $dateTime->format('s');

        return new static(
            Date::create($year, $month, $day),
            Time::create($hour, $minute, $second),
            Timezone::create($timezone)
        );
    }

    /**
     * Creates instance from a native DateTime
     *
     * @param DateTimeInterface $dateTime A DateTimeInterface instance
     *
     * @return DateTime
     */
    public static function fromNative(DateTimeInterface $dateTime): DateTime
    {
        $year = (int) $dateTime->format('Y');
        $month = (int) $dateTime->format('n');
        $day = (int) $dateTime->format('j');
        $hour = (int) $dateTime->format('G');
        $minute = (int) $dateTime->format('i');
        $second = (int) $dateTime->format('s');
        $timezone = $dateTime->getTimezone();

        return new static(
            Date::create($year, $month, $day),
            Time::create($hour, $minute, $second),
            Timezone::create($timezone)
        );
    }

    /**
     * Creates instance from a timestamp and timezone
     *
     * @param int         $timestamp The timestamp
     * @param string|null $timezone  The timezone string or null for default
     *
     * @return DateTime
     */
    public static function fromTimestamp(int $timestamp, string $timezone = null): DateTime
    {
        $timezone = $timezone ?: date_default_timezone_get();
        assert(Test::isTimezone($timezone), sprintf('Invalid timezone: %s', $timezone));

        $time = sprintf('%d', $timestamp);
        $dateTime = DateTimeImmutable::createFromFormat('U', $time, new DateTimeZone('UTC'));
        $dateTime = $dateTime->setTimezone(new DateTimeZone($timezone));
        $year = (int) $dateTime->format('Y');
        $month = (int) $dateTime->format('n');
        $day = (int) $dateTime->format('j');
        $hour = (int) $dateTime->format('G');
        $minute = (int) $dateTime->format('i');
        $second = (int) $dateTime->format('s');

        return new static(
            Date::create($year, $month, $day),
            Time::create($hour, $minute, $second),
            Timezone::create($timezone)
        );
    }

    /**
     * Creates instance from a date/time string
     *
     * @param string $value The date/time string
     *
     * @return DateTime
     *
     * @throws DomainException When the date/time is not formatted correctly
     * @throws DomainException When the date/time is invalid
     */
    public static function fromString(string $value): DateTime
    {
        $pattern = sprintf(
            '/\A%s-%s-%sT%s:%s:%s\[%s\]\z/',
            '(?P<year>[\d]{4})',
            '(?P<month>[\d]{2})',
            '(?P<day>[\d]{2})',
            '(?P<hour>[\d]{2})',
            '(?P<minute>[\d]{2})',
            '(?P<second>[\d]{2})',
            '(?P<timezone>.+)'
        );
        if (!preg_match($pattern, $value, $matches)) {
            $message = sprintf('%s expects $value in "Y-m-d\TH:i:s[e]" format', __METHOD__);
            throw new DomainException($message);
        }

        $year = (int) $matches['year'];
        $month = (int) $matches['month'];
        $day = (int) $matches['day'];
        $hour = (int) $matches['hour'];
        $minute = (int) $matches['minute'];
        $second = (int) $matches['second'];
        $timezone = $matches['timezone'];

        return new static(
            Date::create($year, $month, $day),
            Time::create($hour, $minute, $second),
            Timezone::create($timezone)
        );
    }

    /**
     * Creates instance with a given date
     *
     * @param Date $date The date
     *
     * @return DateTime
     */
    public function withDate(Date $date): DateTime
    {
        return new static($date, $this->time(), $this->timezone());
    }

    /**
     * Creates instance with a given year
     *
     * @param int $year The year
     *
     * @return DateTime
     *
     * @throws DomainException When the date/time is not valid
     */
    public function withYear(int $year): DateTime
    {
        return new static(
            Date::create($year, $this->month(), $this->day()),
            $this->time(),
            $this->timezone()
        );
    }

    /**
     * Creates instance with a given month
     *
     * @param int $month The month
     *
     * @return DateTime
     *
     * @throws DomainException When the date/time is not valid
     */
    public function withMonth(int $month): DateTime
    {
        return new static(
            Date::create($this->year(), $month, $this->day()),
            $this->time(),
            $this->timezone()
        );
    }

    /**
     * Creates instance with a given day
     *
     * @param int $day The day
     *
     * @return DateTime
     *
     * @throws DomainException When the date/time is not valid
     */
    public function withDay(int $day): DateTime
    {
        return new static(
            Date::create($this->year(), $this->month(), $day),
            $this->time(),
            $this->timezone()
        );
    }

    /**
     * Creates instance with a given time
     *
     * @param Time $time The time
     *
     * @return DateTime
     */
    public function withTime(Time $time): DateTime
    {
        return new static($this->date(), $time, $this->timezone());
    }

    /**
     * Creates instance with a given hour
     *
     * @param int $hour The hour
     *
     * @return DateTime
     *
     * @throws DomainException When the date/time is not valid
     */
    public function withHour(int $hour): DateTime
    {
        return new static(
            $this->date(),
            Time::create($hour, $this->minute(), $this->second()),
            $this->timezone()
        );
    }

    /**
     * Creates instance with a given minute
     *
     * @param int $minute The minute
     *
     * @return DateTime
     *
     * @throws DomainException When the date/time is not valid
     */
    public function withMinute(int $minute): DateTime
    {
        return new static(
            $this->date(),
            Time::create($this->hour(), $minute, $this->second()),
            $this->timezone()
        );
    }

    /**
     * Creates instance with a given second
     *
     * @param int $second The second
     *
     * @return DateTime
     *
     * @throws DomainException When the date/time is not valid
     */
    public function withSecond(int $second): DateTime
    {
        return new static(
            $this->date(),
            Time::create($this->hour(), $this->minute(), $second),
            $this->timezone()
        );
    }

    /**
     * Creates instance with a given timezone
     *
     * Note: This method does not convert the date/time values
     *
     * @param mixed $timezone The timezone value
     *
     * @return DateTime
     *
     * @throws DomainException When the timezone is not valid
     */
    public function withTimezone($timezone): DateTime
    {
        return new static(
            $this->date(),
            $this->time(),
            Timezone::create($timezone)
        );
    }

    /**
     * Creates instance converted to a given timezone
     *
     * @param mixed $timezone The timezone value
     *
     * @return DateTime
     *
     * @throws DomainException When the timezone is not valid
     */
    public function toTimezone($timezone): DateTime
    {
        $timestamp = $this->timestamp();

        return static::fromTimestamp($timestamp, $timezone);
    }

    /**
     * Retrieves formatted string representation
     *
     * Returns string formatted according to PHP date() function.
     *
     * @link http://php.net/date PHP date function
     *
     * @param string $format The format string
     *
     * @return string
     */
    public function format(string $format): string
    {
        return $this->dateTime()->format($format);
    }

    /**
     * Retrieves localized formatted string representation
     *
     * Returns string formatted according to PHP strftime() function. Set the
     * current locale using the setlocale() function.
     *
     * @link http://php.net/strftime PHP strftime function
     * @link http://php.net/setlocale PHP setlocale function
     *
     * @param string $format The format string
     *
     * @return string
     */
    public function localeFormat(string $format): string
    {
        // http://php.net/manual/en/function.strftime.php#refsect1-function.strftime-examples
        // Example #3 Cross platform compatible example using the %e modifier
        // @codeCoverageIgnoreStart
        if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
            $format = preg_replace('#(?<!%)((?:%%)*)%e#', '\1%#d', $format);
        }
        // @codeCoverageIgnoreEnd

        return strftime($format, $this->timestamp());
    }

    /**
     * Retrieves ISO-8601 string representation
     *
     * @return string
     */
    public function iso8601(): string
    {
        return $this->format('Y-m-d\TH:i:sP');
    }

    /**
     * Retrieves the date
     *
     * @return Date
     */
    public function date(): Date
    {
        return $this->date;
    }

    /**
     * Retrieves the time
     *
     * @return Time
     */
    public function time(): Time
    {
        return $this->time;
    }

    /**
     * Retrieves the timezone
     *
     * @return Timezone
     */
    public function timezone(): Timezone
    {
        return $this->timezone;
    }

    /**
     * Retrieves the timezone offset in seconds
     *
     * The offset for timezones west of UTC is always negative, and for those
     * east of UTC is always positive.
     *
     * @return int
     */
    public function timezoneOffset(): int
    {
        return (int) $this->format('Z');
    }

    /**
     * Retrieves the year
     *
     * @return int
     */
    public function year(): int
    {
        return $this->date->year();
    }

    /**
     * Retrieves the month
     *
     * @return int
     */
    public function month(): int
    {
        return $this->date->month();
    }

    /**
     * Retrieves the month name
     *
     * Set the current locale using the setlocale() function.
     *
     * @link http://php.net/setlocale PHP setlocale function
     *
     * @return string
     */
    public function monthName(): string
    {
        return strftime('%B', $this->timestamp());
    }

    /**
     * Retrieves the month abbreviation
     *
     * Set the current locale using the setlocale() function.
     *
     * @link http://php.net/setlocale PHP setlocale function
     *
     * @return string
     */
    public function monthAbbr(): string
    {
        return strftime('%b', $this->timestamp());
    }

    /**
     * Retrieves the day
     *
     * @return int
     */
    public function day(): int
    {
        return $this->date->day();
    }

    /**
     * Retrieves the hour
     *
     * @return int
     */
    public function hour(): int
    {
        return $this->time->hour();
    }

    /**
     * Retrieves the minute
     *
     * @return int
     */
    public function minute(): int
    {
        return $this->time->minute();
    }

    /**
     * Retrieves the second
     *
     * @return int
     */
    public function second(): int
    {
        return $this->time->second();
    }

    /**
     * Retrieves the week day
     *
     * From 0 for Sunday to 6 for Saturday.
     *
     * @return int
     */
    public function weekDay(): int
    {
        return (int) $this->format('w');
    }

    /**
     * Retrieves the week day name
     *
     * Set the current locale using the setlocale() function.
     *
     * @link http://php.net/setlocale PHP setlocale function
     *
     * @return string
     */
    public function weekDayName(): string
    {
        return strftime('%A', $this->timestamp());
    }

    /**
     * Retrieves the week day abbreviation
     *
     * Set the current locale using the setlocale() function.
     *
     * @link http://php.net/setlocale PHP setlocale function
     *
     * @return string
     */
    public function weekDayAbbr(): string
    {
        return strftime('%a', $this->timestamp());
    }

    /**
     * Retrieves seconds since the Unix Epoch
     *
     * @return int
     */
    public function timestamp(): int
    {
        return $this->dateTime()->getTimestamp();
    }

    /**
     * Retrieves the day of the year
     *
     * Days are numbered starting with 0.
     *
     * @return int
     */
    public function dayOfYear(): int
    {
        return (int) $this->format('z');
    }

    /**
     * Retrieves ISO-8601 week number of the year
     *
     * @return int
     */
    public function weekNumber(): int
    {
        return (int) $this->format('W');
    }

    /**
     * Retrieves the number of days in the month
     *
     * @return int
     */
    public function daysInMonth(): int
    {
        return (int) $this->format('t');
    }

    /**
     * Checks if the year is a leap year
     *
     * @return bool
     */
    public function isLeapYear(): bool
    {
        if ($this->format('L') == '1') {
            return true;
        }

        return false;
    }

    /**
     * Checks if the date is in daylight savings time
     *
     * @return bool
     */
    public function isDaylightSavings(): bool
    {
        if ($this->format('I') == '1') {
            return true;
        }

        return false;
    }

    /**
     * Creates an instance with a modified timestamp
     *
     * Alters the timestamp of the DateTime by incrementing or decrementing in
     * a format accepted by strtotime().
     *
     * @param string $modify A date/time string
     *
     * @return DateTime
     */
    public function modify(string $modify): DateTime
    {
        $dateTime = $this->dateTime()->modify($modify);

        return static::fromNative($dateTime);
    }

    /**
     * Retrieves a native DateTime instance
     *
     * @return DateTimeInterface
     */
    public function toNative(): DateTimeInterface
    {
        $time = sprintf(
            '%04d-%02d-%02dT%02d:%02d:%02d',
            $this->year(),
            $this->month(),
            $this->day(),
            $this->hour(),
            $this->minute(),
            $this->second()
        );

        return NativeDateTime::createFromFormat(
            static::STRING_FORMAT,
            $time,
            new DateTimeZone($this->timezone->toString())
        );
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        return sprintf('%s[%s]', $this->format(static::STRING_FORMAT), $this->timezone->toString());
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

        $thisStamp = $this->timestamp();
        $thatStamp = $object->timestamp();

        if ($thisStamp > $thatStamp) {
            return 1;
        }
        if ($thisStamp < $thatStamp) {
            return -1;
        }

        return $this->timezone->compareTo($object->timezone);
    }

    /**
     * Retrieves a native DateTime instance
     *
     * @return DateTimeImmutable
     */
    protected function dateTime(): DateTimeImmutable
    {
        if ($this->dateTime === null) {
            $year = $this->year();
            $month = $this->month();
            $day = $this->day();
            $hour = $this->hour();
            $minute = $this->minute();
            $second = $this->second();
            $timezone = $this->timezone()->toString();
            $this->dateTime = self::createNative($year, $month, $day, $hour, $minute, $second, $timezone);
        }

        return $this->dateTime;
    }

    /**
     * Creates a native DateTime from date and time values
     *
     * @param int    $year     The year
     * @param int    $month    The month
     * @param int    $day      The day
     * @param int    $hour     The hour
     * @param int    $minute   The minute
     * @param int    $second   The second
     * @param string $timezone The timezone
     *
     * @return DateTimeImmutable
     */
    private static function createNative(
        int $year,
        int $month,
        int $day,
        int $hour,
        int $minute,
        int $second,
        string $timezone
    ): DateTimeImmutable {
        $time = sprintf('%04d-%02d-%02dT%02d:%02d:%02d', $year, $month, $day, $hour, $minute, $second);

        return DateTimeImmutable::createFromFormat(self::STRING_FORMAT, $time, new DateTimeZone($timezone));
    }
}
