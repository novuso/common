<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Value\DateTime;

use DateTime as NativeDateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Exception;
use Novuso\Common\Domain\Type\ValueObject;
use Novuso\System\Exception\DomainException;
use Novuso\System\Type\Comparable;
use Novuso\System\Utility\Assert;

/**
 * Class DateTime
 */
final class DateTime extends ValueObject implements Comparable
{
    /**
     * String format
     *
     * @var string
     */
    public const STRING_FORMAT = 'Y-m-d\TH:i:s.u[e]';

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
     * @param int         $year        The year
     * @param int         $month       The month
     * @param int         $day         The day
     * @param int         $hour        The hour
     * @param int         $minute      The minute
     * @param int         $second      The second
     * @param int         $microsecond The microsecond
     * @param string|null $timezone    The timezone string or null for default
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
        int $microsecond = 0,
        ?string $timezone = null
    ): DateTime {
        try {
            $timezone = $timezone ?: date_default_timezone_get();
            Assert::isTimezone($timezone);

            $date = Date::create($year, $month, $day);
            $time = Time::create($hour, $minute, $second, $microsecond);
            $timezone = Timezone::create($timezone);

            return new static($date, $time, $timezone);
        } catch (Exception $e) {
            throw new DomainException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Creates instance for the current date and time
     *
     * @param string|null $timezone The timezone string or null for default
     *
     * @return DateTime
     *
     * @throws DomainException When the date/time or timezone is not valid
     */
    public static function now(?string $timezone = null): DateTime
    {
        try {
            $timezone = $timezone ?: date_default_timezone_get();
            Assert::isTimezone($timezone);

            $dateTime = new DateTimeImmutable('now', new DateTimeZone($timezone));
            $year = (int) $dateTime->format('Y');
            $month = (int) $dateTime->format('n');
            $day = (int) $dateTime->format('j');
            $hour = (int) $dateTime->format('G');
            $minute = (int) $dateTime->format('i');
            $second = (int) $dateTime->format('s');
            $microsecond = (int) $dateTime->format('u');

            return new static(
                Date::create($year, $month, $day),
                Time::create($hour, $minute, $second, $microsecond),
                Timezone::create($timezone)
            );
        } catch (Exception $e) {
            throw new DomainException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Creates instance from date/time string in a given format
     *
     * @param string      $format   The format
     * @param string      $time     The date/time string
     * @param string|null $timezone The timezone string or null for default
     *
     * @return DateTime
     *
     * @throws DomainException When the date/time or timezone is not valid
     */
    public static function fromFormat($format, $time, $timezone = null): DateTime
    {
        try {
            $timezone = $timezone ?: date_default_timezone_get();
            Assert::isTimezone($timezone);

            $dateTime = DateTimeImmutable::createFromFormat($format, $time, new DateTimeZone($timezone));

            if ($dateTime === false) {
                $message = sprintf('Invalid data for format "%s": "%s"', $format, $time);
                throw new DomainException($message);
            }

            $year = (int) $dateTime->format('Y');
            $month = (int) $dateTime->format('n');
            $day = (int) $dateTime->format('j');
            $hour = (int) $dateTime->format('G');
            $minute = (int) $dateTime->format('i');
            $second = (int) $dateTime->format('s');
            $microsecond = (int) $dateTime->format('u');
            $timezone = $dateTime->getTimezone();

            return new static(
                Date::create($year, $month, $day),
                Time::create($hour, $minute, $second, $microsecond),
                Timezone::create($timezone)
            );
        } catch (Exception $e) {
            throw new DomainException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Creates instance from a native DateTime
     *
     * @param DateTimeInterface $dateTime A DateTimeInterface instance
     *
     * @return DateTime
     *
     * @throws DomainException When the timezone is not valid
     */
    public static function fromNative(DateTimeInterface $dateTime): DateTime
    {
        $year = (int) $dateTime->format('Y');
        $month = (int) $dateTime->format('n');
        $day = (int) $dateTime->format('j');
        $hour = (int) $dateTime->format('G');
        $minute = (int) $dateTime->format('i');
        $second = (int) $dateTime->format('s');
        $microsecond = (int) $dateTime->format('u');
        $timezone = $dateTime->getTimezone();

        return new static(
            Date::create($year, $month, $day),
            Time::create($hour, $minute, $second, $microsecond),
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
     *
     * @throws DomainException When the date/time or timezone is not valid
     */
    public static function fromTimestamp(int $timestamp, ?string $timezone = null): DateTime
    {
        try {
            $timezone = $timezone ?: date_default_timezone_get();
            Assert::isTimezone($timezone);

            $time = sprintf('%d', $timestamp);
            $dateTime = DateTimeImmutable::createFromFormat('U', $time, new DateTimeZone('UTC'));
            $dateTime = $dateTime->setTimezone(new DateTimeZone($timezone));
            $year = (int) $dateTime->format('Y');
            $month = (int) $dateTime->format('n');
            $day = (int) $dateTime->format('j');
            $hour = (int) $dateTime->format('G');
            $minute = (int) $dateTime->format('i');
            $second = (int) $dateTime->format('s');
            $microsecond = (int) $dateTime->format('u');

            return new static(
                Date::create($year, $month, $day),
                Time::create($hour, $minute, $second, $microsecond),
                Timezone::create($timezone)
            );
        } catch (Exception $e) {
            throw new DomainException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function fromString(string $value): DateTime
    {
        $pattern = sprintf(
            '/\A%s-%s-%sT%s:%s:%s\.%s\[%s\]\z/',
            '(?P<year>[\d]{4})',
            '(?P<month>[\d]{2})',
            '(?P<day>[\d]{2})',
            '(?P<hour>[\d]{2})',
            '(?P<minute>[\d]{2})',
            '(?P<second>[\d]{2})',
            '(?P<microsecond>[\d]{6})',
            '(?P<timezone>.+)'
        );
        if (!preg_match($pattern, $value, $matches)) {
            $message = sprintf('%s expects $value in "Y-m-d\TH:i:s.u[e]" format', __METHOD__);
            throw new DomainException($message);
        }

        $year = (int) $matches['year'];
        $month = (int) $matches['month'];
        $day = (int) $matches['day'];
        $hour = (int) $matches['hour'];
        $minute = (int) $matches['minute'];
        $second = (int) $matches['second'];
        $microsecond = (int) $matches['microsecond'];
        $timezone = $matches['timezone'];

        return new static(
            Date::create($year, $month, $day),
            Time::create($hour, $minute, $second, $microsecond),
            Timezone::create($timezone)
        );
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
     *
     * @throws DomainException When the modify string is invalid
     */
    public function modify(string $modify): DateTime
    {
        try {
            $dateTime = $this->dateTime()->modify($modify);

            // @codeCoverageIgnoreStart
            if ($dateTime === false) {
                $message = sprintf('Invalid modify string: %s', $modify);
                throw new DomainException($message);
            }
            // @codeCoverageIgnoreEnd

            $year = (int) $dateTime->format('Y');
            $month = (int) $dateTime->format('n');
            $day = (int) $dateTime->format('j');
            $hour = (int) $dateTime->format('G');
            $minute = (int) $dateTime->format('i');
            $second = (int) $dateTime->format('s');
            $microsecond = (int) $dateTime->format('u');
            $timezone = $dateTime->getTimezone();

            return new static(
                Date::create($year, $month, $day),
                Time::create($hour, $minute, $second, $microsecond),
                Timezone::create($timezone)
            );
        } catch (Exception $e) {
            throw new DomainException($e->getMessage(), $e->getCode(), $e);
        }
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
            Time::create($hour, $this->minute(), $this->second(), $this->microsecond()),
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
            Time::create($this->hour(), $minute, $this->second(), $this->microsecond()),
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
            Time::create($this->hour(), $this->minute(), $second, $this->microsecond()),
            $this->timezone()
        );
    }

    /**
     * Creates instance with a given microsecond
     *
     * @param int $microsecond The microsecond
     *
     * @return DateTime
     *
     * @throws DomainException When the date/time is not valid
     */
    public function withMicrosecond(int $microsecond): DateTime
    {
        return new static(
            $this->date(),
            Time::create($this->hour(), $this->minute(), $this->second(), $microsecond),
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
        if ($timezone instanceof DateTimeZone) {
            $timezone = $timezone->getName();
        }

        $timestamp = $this->timestamp();
        $microsecond = $this->microsecond();

        $dateTime = static::fromTimestamp($timestamp, $timezone);

        return $dateTime->withMicrosecond($microsecond);
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
        return strftime($format, $this->timestamp());
    }

    /**
     * Retrieves ISO-8601 string representation
     *
     * @return string
     */
    public function iso8601(): string
    {
        return $this->format(DATE_ATOM);
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
    public function monthAbbreviation(): string
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
     * Retrieves the microsecond
     *
     * @return int
     */
    public function microsecond(): int
    {
        return $this->time->microsecond();
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
    public function weekDayAbbreviation(): string
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
     * Retrieves a native DateTime instance
     *
     * @return DateTimeInterface
     */
    public function toNative(): DateTimeInterface
    {
        $time = sprintf(
            '%04d-%02d-%02d %02d:%02d:%02d.%06d',
            $this->year(),
            $this->month(),
            $this->day(),
            $this->hour(),
            $this->minute(),
            $this->second(),
            $this->microsecond()
        );

        return NativeDateTime::createFromFormat(
            'Y-m-d H:i:s.u',
            $time,
            new DateTimeZone($this->timezone->toString())
        );
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        return $this->format(static::STRING_FORMAT);
    }

    /**
     * {@inheritdoc}
     */
    public function compareTo($object): int
    {
        if ($this === $object) {
            return 0;
        }

        Assert::areSameType($this, $object);

        $thisStamp = $this->timestamp();
        $thatStamp = $object->timestamp();

        if ($thisStamp > $thatStamp) {
            return 1;
        }
        if ($thisStamp < $thatStamp) {
            return -1;
        }

        $thisMicro = $this->microsecond();
        $thatMicro = $object->microsecond();

        if ($thisMicro > $thatMicro) {
            return 1;
        }
        if ($thisMicro < $thatMicro) {
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
            $microsecond = $this->microsecond();
            $timezone = $this->timezone()->toString();
            $this->dateTime = self::createNative(
                $year,
                $month,
                $day,
                $hour,
                $minute,
                $second,
                $microsecond,
                $timezone
            );
        }

        return $this->dateTime;
    }

    /**
     * Creates a native DateTime from date and time values
     *
     * @param int    $year        The year
     * @param int    $month       The month
     * @param int    $day         The day
     * @param int    $hour        The hour
     * @param int    $minute      The minute
     * @param int    $second      The second
     * @param int    $microsecond The microsecond
     * @param string $timezone    The timezone
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
        int $microsecond,
        string $timezone
    ): DateTimeImmutable {
        $format = 'Y-m-d\TH:i:s.u';
        $time = sprintf(
            '%04d-%02d-%02dT%02d:%02d:%02d.%06d',
            $year,
            $month,
            $day,
            $hour,
            $minute,
            $second,
            $microsecond
        );

        return DateTimeImmutable::createFromFormat($format, $time, new DateTimeZone($timezone));
    }
}
