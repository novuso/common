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
 * Class Time
 */
final class Time extends ValueObject implements Comparable
{
    protected const MIN_HOUR = 0;
    protected const MAX_HOUR = 23;
    protected const MIN_MINUTE = 0;
    protected const MAX_MINUTE = 59;
    protected const MIN_SECOND = 0;
    protected const MAX_SECOND = 59;
    protected const MIN_MICROSECOND = 0;
    protected const MAX_MICROSECOND = 999999;

    /**
     * Constructs Time
     *
     * @throws DomainException When the time is not valid
     */
    public function __construct(
        protected int $hour,
        protected int $minute,
        protected int $second,
        protected int $microsecond = 0
    ) {
        $this->guardTime(
            $this->hour,
            $this->minute,
            $this->second,
            $this->microsecond
        );
    }

    /**
     * Creates instance from time values
     *
     * @throws DomainException When the time is not valid
     */
    public static function create(
        int $hour,
        int $minute,
        int $second,
        int $microsecond = 0
    ): static {
        return new static($hour, $minute, $second, $microsecond);
    }

    /**
     * Creates instance for the current time
     */
    public static function now(?string $timezone = null): static
    {
        $timezone = $timezone ?: date_default_timezone_get();
        Assert::isTimezone($timezone);

        $dateTime = new DateTimeImmutable('now', new DateTimeZone($timezone));
        $hour = (int) $dateTime->format('G');
        $minute = (int) $dateTime->format('i');
        $second = (int) $dateTime->format('s');
        $microsecond = (int) $dateTime->format('u');

        return new static($hour, $minute, $second, $microsecond);
    }

    /**
     * Creates an instance from a native DateTime
     */
    public static function fromNative(DateTimeInterface $dateTime): static
    {
        $hour = (int) $dateTime->format('G');
        $minute = (int) $dateTime->format('i');
        $second = (int) $dateTime->format('s');
        $microsecond = (int) $dateTime->format('u');

        return new static($hour, $minute, $second, $microsecond);
    }

    /**
     * Creates instance from a timestamp and timezone
     */
    public static function fromTimestamp(
        int $timestamp,
        ?string $timezone = null
    ): static {
        $timezone = $timezone ?: date_default_timezone_get();
        Assert::isTimezone($timezone);

        $time = sprintf('%d', $timestamp);
        $dateTime = DateTimeImmutable::createFromFormat(
            'U',
            $time,
            new DateTimeZone('UTC')
        );
        $dateTime = $dateTime->setTimezone(new DateTimeZone($timezone));
        $hour = (int) $dateTime->format('G');
        $minute = (int) $dateTime->format('i');
        $second = (int) $dateTime->format('s');
        $microsecond = (int) $dateTime->format('u');

        return new static($hour, $minute, $second, $microsecond);
    }

    /**
     * @inheritDoc
     */
    public static function fromString(string $value): static
    {
        $pattern = sprintf(
            '/\A%s:%s:%s\.%s\z/',
            '(?P<hour>[\d]{2})',
            '(?P<minute>[\d]{2})',
            '(?P<second>[\d]{2})',
            '(?P<microsecond>[\d]{6})'
        );
        if (!preg_match($pattern, $value, $matches)) {
            $message = sprintf(
                '%s expects $value in "H:i:s.u" format',
                __METHOD__
            );
            throw new DomainException($message);
        }

        $hour = (int) $matches['hour'];
        $minute = (int) $matches['minute'];
        $second = (int) $matches['second'];
        $microsecond = (int) $matches['microsecond'];

        return new static($hour, $minute, $second, $microsecond);
    }

    /**
     * Creates instance with a given hour
     *
     * @throws DomainException When the time is not valid
     */
    public function withHour(int $hour): static
    {
        return new static(
            $hour,
            $this->minute(),
            $this->second(),
            $this->microsecond()
        );
    }

    /**
     * Creates instance with a given minute
     *
     * @throws DomainException When the time is not valid
     */
    public function withMinute(int $minute): static
    {
        return new static(
            $this->hour(),
            $minute,
            $this->second(),
            $this->microsecond()
        );
    }

    /**
     * Creates instance with a given second
     *
     * @throws DomainException When the time is not valid
     */
    public function withSecond(int $second): static
    {
        return new static(
            $this->hour(),
            $this->minute(),
            $second,
            $this->microsecond()
        );
    }

    /**
     * Creates instance with a given microsecond
     *
     * @throws DomainException When the time is not valid
     */
    public function withMicrosecond(int $microsecond): static
    {
        return new static(
            $this->hour(),
            $this->minute(),
            $this->second(),
            $microsecond
        );
    }

    /**
     * Retrieves the hour
     */
    public function hour(): int
    {
        return $this->hour;
    }

    /**
     * Retrieves the minute
     */
    public function minute(): int
    {
        return $this->minute;
    }

    /**
     * Retrieves the second
     */
    public function second(): int
    {
        return $this->second;
    }

    /**
     * Retrieves the microsecond
     */
    public function microsecond(): int
    {
        return $this->microsecond;
    }

    /**
     * Checks if this time is before the given time
     */
    public function isBefore(Time $time): bool
    {
        return $this->compareTo($time) < 0;
    }

    /**
     * Checks if this time is same as the given time
     */
    public function isSame(Time $time): bool
    {
        return $this->compareTo($time) === 0;
    }

    /**
     * Checks if this time is after the given time
     */
    public function isAfter(Time $time): bool
    {
        return $this->compareTo($time) > 0;
    }

    /**
     * Checks if this time is same or before the given time
     */
    public function isSameOrBefore(Time $time): bool
    {
        return $this->compareTo($time) <= 0;
    }

    /**
     * Checks if this time is same or after the given time
     */
    public function isSameOrAfter(Time $time): bool
    {
        return $this->compareTo($time) >= 0;
    }

    /**
     * Checks if this time is between the given times
     *
     * @throws DomainException When the given times are invalid
     */
    public function isBetween(Time $startTime, Time $endTime): bool
    {
        if ($startTime->isAfter($endTime)) {
            throw new DomainException('Start time must come before end time');
        }

        return $this->isSameOrAfter($startTime)
            && $this->isSameOrBefore($endTime);
    }

    /**
     * @inheritDoc
     */
    public function toString(): string
    {
        return sprintf(
            '%02d:%02d:%02d.%06d',
            $this->hour,
            $this->minute,
            $this->second,
            $this->microsecond
        );
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

        if ($this->hour > $object->hour) {
            return 1;
        }
        if ($this->hour < $object->hour) {
            return -1;
        }

        if ($this->minute > $object->minute) {
            return 1;
        }
        if ($this->minute < $object->minute) {
            return -1;
        }

        if ($this->second > $object->second) {
            return 1;
        }
        if ($this->second < $object->second) {
            return -1;
        }

        if ($this->microsecond > $object->microsecond) {
            return 1;
        }
        if ($this->microsecond < $object->microsecond) {
            return -1;
        }

        return 0;
    }

    /**
     * Validates the time
     *
     * @throws DomainException When the time is not valid
     */
    protected function guardTime(
        int $hour,
        int $minute,
        int $second,
        int $microsecond
    ): void {
        if ($hour < static::MIN_HOUR || $hour > static::MAX_HOUR) {
            $message = sprintf(
                'Hour (%d) out of range[%d, %d]',
                $hour,
                static::MIN_HOUR,
                static::MAX_HOUR
            );
            throw new DomainException($message);
        }

        if ($minute < static::MIN_MINUTE || $minute > static::MAX_MINUTE) {
            $message = sprintf(
                'Minute (%d) out of range[%d, %d]',
                $minute,
                static::MIN_MINUTE,
                static::MAX_MINUTE
            );
            throw new DomainException($message);
        }

        if ($second < static::MIN_SECOND || $second > static::MAX_SECOND) {
            $message = sprintf(
                'Second (%d) out of range[%d, %d]',
                $second,
                static::MIN_SECOND,
                static::MAX_SECOND
            );
            throw new DomainException($message);
        }

        if (
            $microsecond < static::MIN_MICROSECOND
            || $microsecond > static::MAX_MICROSECOND
        ) {
            $message = sprintf(
                'Microsecond (%d) out of range[%d, %d]',
                $microsecond,
                static::MIN_MICROSECOND,
                static::MAX_MICROSECOND
            );
            throw new DomainException($message);
        }
    }
}
