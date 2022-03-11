<?php

declare(strict_types=1);

namespace Novuso\Common\Domain\Value\DateTime;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Novuso\Common\Domain\Type\ValueObject;
use Novuso\System\Exception\DomainException;
use Novuso\System\Type\Comparable;
use Novuso\System\Utility\Assert;

/**
 * Class Date
 */
final class Date extends ValueObject implements Comparable
{
    /**
     * Constructs Date
     *
     * @throws DomainException When the date is not valid
     */
    public function __construct(
        protected int $year,
        protected int $month,
        protected int $day
    ) {
        $this->guardDate($this->year, $this->month, $this->day);
    }

    /**
     * Creates instance from date values
     *
     * @throws DomainException When the date is not valid
     */
    public static function create(int $year, int $month, int $day): Date
    {
        return new static($year, $month, $day);
    }

    /**
     * Creates instance for the current date
     */
    public static function now(?string $timezone = null): Date
    {
        $timezone = $timezone ?: date_default_timezone_get();
        Assert::isTimezone($timezone);

        $dateTime = new DateTimeImmutable('now', new DateTimeZone($timezone));
        $year = (int) $dateTime->format('Y');
        $month = (int) $dateTime->format('n');
        $day = (int) $dateTime->format('j');

        return new static($year, $month, $day);
    }

    /**
     * Creates instance from a native DateTime
     */
    public static function fromNative(DateTimeInterface $dateTime): Date
    {
        $year = (int) $dateTime->format('Y');
        $month = (int) $dateTime->format('n');
        $day = (int) $dateTime->format('j');

        return new static($year, $month, $day);
    }

    /**
     * Creates instance from a timestamp and timezone
     */
    public static function fromTimestamp(
        int $timestamp,
        ?string $timezone = null
    ): Date {
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

        return new static($year, $month, $day);
    }

    /**
     * @inheritDoc
     */
    public static function fromString(string $value): static
    {
        $pattern = '/\A(?P<year>[\d]{4})-(?P<month>[\d]{2})-(?P<day>[\d]{2})\z/';
        if (!preg_match($pattern, $value, $matches)) {
            $message = sprintf(
                '%s expects $value in "Y-m-d" format',
                __METHOD__
            );
            throw new DomainException($message);
        }

        $year = (int) $matches['year'];
        $month = (int) $matches['month'];
        $day = (int) $matches['day'];

        return new static($year, $month, $day);
    }

    /**
     * Creates instance with a given year
     *
     * @throws DomainException When the date is not valid
     */
    public function withYear(int $year): static
    {
        return new static($year, $this->month(), $this->day());
    }

    /**
     * Creates instance with a given month
     *
     * @throws DomainException When the date is not valid
     */
    public function withMonth(int $month): static
    {
        return new static($this->year(), $month, $this->day());
    }

    /**
     * Creates instance with a given day
     *
     * @throws DomainException When the date is not valid
     */
    public function withDay(int $day): static
    {
        return new static($this->year(), $this->month(), $day);
    }

    /**
     * Retrieves the year
     */
    public function year(): int
    {
        return $this->year;
    }

    /**
     * Retrieves the month
     */
    public function month(): int
    {
        return $this->month;
    }

    /**
     * Retrieves the day
     */
    public function day(): int
    {
        return $this->day;
    }

    /**
     * Retrieves the week day
     */
    public function weekDay(): WeekDay
    {
        return WeekDay::fromValue((int) date('w', strtotime(sprintf(
            '%s-%s-%s',
            $this->year,
            $this->month,
            $this->day
        ))));
    }

    /**
     * Retrieves the week day name
     */
    public function weekDayName(): string
    {
        return date('l', strtotime(sprintf(
            '%s-%s-%s',
            $this->year,
            $this->month,
            $this->day
        )));
    }

    /**
     * Checks if this date is before the given date
     */
    public function isBefore(Date $date): bool
    {
        return $this->compareTo($date) < 0;
    }

    /**
     * Checks if this date is same as the given date
     */
    public function isSame(Date $date): bool
    {
        return $this->compareTo($date) === 0;
    }

    /**
     * Checks if this date is after the given date
     */
    public function isAfter(Date $date): bool
    {
        return $this->compareTo($date) > 0;
    }

    /**
     * Checks if this date is same or before the given date
     */
    public function isSameOrBefore(Date $date): bool
    {
        return $this->compareTo($date) <= 0;
    }

    /**
     * Checks if this date is same or after the given date
     */
    public function isSameOrAfter(Date $date): bool
    {
        return $this->compareTo($date) >= 0;
    }

    /**
     * Checks if this date is between the given dates
     *
     * @throws DomainException When the given dates are invalid
     */
    public function isBetween(Date $startDate, Date $endDate): bool
    {
        if ($startDate->isAfter($endDate)) {
            throw new DomainException('Start date must come before end date');
        }

        return $this->isSameOrAfter($startDate)
            && $this->isSameOrBefore($endDate);
    }

    /**
     * @inheritDoc
     */
    public function toString(): string
    {
        return sprintf('%04d-%02d-%02d', $this->year, $this->month, $this->day);
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

        if ($this->year > $object->year) {
            return 1;
        }
        if ($this->year < $object->year) {
            return -1;
        }

        if ($this->month > $object->month) {
            return 1;
        }
        if ($this->month < $object->month) {
            return -1;
        }

        if ($this->day > $object->day) {
            return 1;
        }
        if ($this->day < $object->day) {
            return -1;
        }

        return 0;
    }

    /**
     * Validates the date
     *
     * @throws DomainException When the date is not valid
     */
    protected function guardDate(int $year, int $month, int $day): void
    {
        if (!checkdate($month, $day, $year)) {
            $message = sprintf(
                'Invalid date: %04d-%02d-%02d',
                $year,
                $month,
                $day
            );
            throw new DomainException($message);
        }
    }
}
