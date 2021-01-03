<?php

declare(strict_types=1);

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
    public const STRING_FORMAT = 'Y-m-d\TH:i:s.u[e]';

    protected ?DateTimeImmutable $dateTime = null;

    /**
     * Constructs DateTime
     */
    public function __construct(
        protected Date $date,
        protected Time $time,
        protected Timezone $timezone
    ) {
    }

    /**
     * Creates instance from date and time values
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
    ): static {
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
     * @throws DomainException When the date/time or timezone is not valid
     */
    public static function now(?string $timezone = null): static
    {
        try {
            $timezone = $timezone ?: date_default_timezone_get();
            Assert::isTimezone($timezone);

            $dateTime = new DateTimeImmutable(
                'now',
                new DateTimeZone($timezone)
            );
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
     * @throws DomainException When the date/time or timezone is not valid
     */
    public static function fromFormat(
        string $format,
        string $time,
        ?string $timezone = null
    ): static {
        try {
            $timezone = $timezone ?: date_default_timezone_get();
            Assert::isTimezone($timezone);

            $dateTime = DateTimeImmutable::createFromFormat(
                $format,
                $time,
                new DateTimeZone($timezone)
            );

            if ($dateTime === false) {
                $message = sprintf(
                    'Invalid data for format "%s": "%s"',
                    $format,
                    $time
                );
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
     * @throws DomainException When the timezone is not valid
     */
    public static function fromNative(DateTimeInterface $dateTime): static
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
     * @throws DomainException When the date/time or timezone is not valid
     */
    public static function fromTimestamp(
        int $timestamp,
        ?string $timezone = null
    ): static {
        try {
            $timezone = $timezone ?: date_default_timezone_get();
            Assert::isTimezone($timezone);

            $time = sprintf('%d', $timestamp);
            $dateTime = DateTimeImmutable::createFromFormat(
                'U',
                $time,
                new DateTimeZone('UTC')
            );
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
     * @inheritDoc
     */
    public static function fromString(string $value): static
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
            $message = sprintf(
                '%s expects $value in "Y-m-d\TH:i:s.u[e]" format',
                __METHOD__
            );
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
     * @throws DomainException When the modify string is invalid
     */
    public function modify(string $modify): static
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
     */
    public function withDate(Date $date): static
    {
        return new static($date, $this->time(), $this->timezone());
    }

    /**
     * Creates instance with a given year
     *
     * @throws DomainException When the date/time is not valid
     */
    public function withYear(int $year): static
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
     * @throws DomainException When the date/time is not valid
     */
    public function withMonth(int $month): static
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
     * @throws DomainException When the date/time is not valid
     */
    public function withDay(int $day): static
    {
        return new static(
            Date::create($this->year(), $this->month(), $day),
            $this->time(),
            $this->timezone()
        );
    }

    /**
     * Creates instance with a given time
     */
    public function withTime(Time $time): static
    {
        return new static($this->date(), $time, $this->timezone());
    }

    /**
     * Creates instance with a given hour
     *
     * @throws DomainException When the date/time is not valid
     */
    public function withHour(int $hour): static
    {
        return new static(
            $this->date(),
            Time::create(
                $hour,
                $this->minute(),
                $this->second(),
                $this->microsecond()
            ),
            $this->timezone()
        );
    }

    /**
     * Creates instance with a given minute
     *
     * @throws DomainException When the date/time is not valid
     */
    public function withMinute(int $minute): static
    {
        return new static(
            $this->date(),
            Time::create(
                $this->hour(),
                $minute,
                $this->second(),
                $this->microsecond()
            ),
            $this->timezone()
        );
    }

    /**
     * Creates instance with a given second
     *
     * @throws DomainException When the date/time is not valid
     */
    public function withSecond(int $second): static
    {
        return new static(
            $this->date(),
            Time::create(
                $this->hour(),
                $this->minute(),
                $second,
                $this->microsecond()
            ),
            $this->timezone()
        );
    }

    /**
     * Creates instance with a given microsecond
     *
     * @throws DomainException When the date/time is not valid
     */
    public function withMicrosecond(int $microsecond): static
    {
        return new static(
            $this->date(),
            Time::create(
                $this->hour(),
                $this->minute(),
                $this->second(),
                $microsecond
            ),
            $this->timezone()
        );
    }

    /**
     * Creates instance with a given timezone
     *
     * Note: This method does not convert the date/time values
     *
     * @throws DomainException When the timezone is not valid
     */
    public function withTimezone(mixed $timezone): static
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
     * @throws DomainException When the timezone is not valid
     */
    public function toTimezone(mixed $timezone): static
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
     */
    public function localeFormat(string $format): string
    {
        return strftime($format, $this->timestamp());
    }

    /**
     * Retrieves ISO-8601 string representation
     */
    public function iso8601(): string
    {
        return $this->format(DateTimeInterface::ATOM);
    }

    /**
     * Retrieves the date
     */
    public function date(): Date
    {
        return $this->date;
    }

    /**
     * Retrieves the time
     */
    public function time(): Time
    {
        return $this->time;
    }

    /**
     * Retrieves the timezone
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
     */
    public function timezoneOffset(): int
    {
        return (int) $this->format('Z');
    }

    /**
     * Retrieves the year
     */
    public function year(): int
    {
        return $this->date->year();
    }

    /**
     * Retrieves the month
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
     */
    public function monthAbbreviation(): string
    {
        return strftime('%b', $this->timestamp());
    }

    /**
     * Retrieves the day
     */
    public function day(): int
    {
        return $this->date->day();
    }

    /**
     * Retrieves the hour
     */
    public function hour(): int
    {
        return $this->time->hour();
    }

    /**
     * Retrieves the minute
     */
    public function minute(): int
    {
        return $this->time->minute();
    }

    /**
     * Retrieves the second
     */
    public function second(): int
    {
        return $this->time->second();
    }

    /**
     * Retrieves the microsecond
     */
    public function microsecond(): int
    {
        return $this->time->microsecond();
    }

    /**
     * Retrieves the week day
     */
    public function weekDay(): WeekDay
    {
        return WeekDay::fromValue((int) $this->format('w'));
    }

    /**
     * Retrieves the week day name
     *
     * Set the current locale using the setlocale() function.
     *
     * @link http://php.net/setlocale PHP setlocale function
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
     */
    public function weekDayAbbreviation(): string
    {
        return strftime('%a', $this->timestamp());
    }

    /**
     * Retrieves seconds since the Unix Epoch
     */
    public function timestamp(): int
    {
        return $this->dateTime()->getTimestamp();
    }

    /**
     * Retrieves the day of the year
     *
     * Days are numbered starting with 0.
     */
    public function dayOfYear(): int
    {
        return (int) $this->format('z');
    }

    /**
     * Retrieves ISO-8601 week number of the year
     */
    public function weekNumber(): int
    {
        return (int) $this->format('W');
    }

    /**
     * Retrieves the number of days in the month
     */
    public function daysInMonth(): int
    {
        return (int) $this->format('t');
    }

    /**
     * Checks if the year is a leap year
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
     */
    public function isDaylightSavings(): bool
    {
        if ($this->format('I') == '1') {
            return true;
        }

        return false;
    }

    /**
     * Checks if this is before the given date/time
     */
    public function isBefore(DateTime $dateTime): bool
    {
        return $this->compareTo($dateTime) < 0;
    }

    /**
     * Checks if this is same as the given date/time
     */
    public function isSame(DateTime $dateTime): bool
    {
        return $this->compareTo($dateTime) === 0;
    }

    /**
     * Checks if this is after the given date/time
     */
    public function isAfter(DateTime $dateTime): bool
    {
        return $this->compareTo($dateTime) > 0;
    }

    /**
     * Checks if this is same or before the given date/time
     */
    public function isSameOrBefore(DateTime $dateTime): bool
    {
        return $this->compareTo($dateTime) <= 0;
    }

    /**
     * Checks if this is same or after the given date/time
     */
    public function isSameOrAfter(DateTime $dateTime): bool
    {
        return $this->compareTo($dateTime) >= 0;
    }

    /**
     * Checks if this is between the given date/times
     *
     * @throws DomainException When the given date/times are invalid
     */
    public function isBetween(
        DateTime $startDateTime,
        DateTime $endDateTime
    ): bool {
        if ($startDateTime->isAfter($endDateTime)) {
            throw new DomainException(
                'Start date/time must come before end date/time'
            );
        }

        return $this->isSameOrAfter($startDateTime)
            && $this->isSameOrBefore($endDateTime);
    }

    /**
     * Retrieves a native DateTime instance
     */
    public function toNative(): NativeDateTime
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
     * Retrieves a DateTimeImmutable instance
     */
    public function toImmutable(): DateTimeImmutable
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

        return DateTimeImmutable::createFromFormat(
            'Y-m-d H:i:s.u',
            $time,
            new DateTimeZone($this->timezone->toString())
        );
    }

    /**
     * @inheritDoc
     */
    public function toString(): string
    {
        return $this->format(static::STRING_FORMAT);
    }

    /**
     * @inheritDoc
     */
    public function compareTo(mixed $object): int
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
     * Retrieves internal DateTimeImmutable instance
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

        return DateTimeImmutable::createFromFormat(
            $format,
            $time,
            new DateTimeZone($timezone)
        );
    }
}
