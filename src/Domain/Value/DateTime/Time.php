<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Value\DateTime;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Novuso\Common\Domain\Type\ValueObject;
use Novuso\System\Exception\AssertionException;
use Novuso\System\Exception\DomainException;
use Novuso\System\Type\Comparable;
use Novuso\System\Utility\Assert;

/**
 * Class Time
 */
final class Time extends ValueObject implements Comparable
{
    /**
     * Minimum hour
     *
     * @var int
     */
    protected const MIN_HOUR = 0;

    /**
     * Maximum hour
     *
     * @var int
     */
    protected const MAX_HOUR = 23;

    /**
     * Minimum minute
     *
     * @var int
     */
    protected const MIN_MINUTE = 0;

    /**
     * Maximum minute
     *
     * @var int
     */
    protected const MAX_MINUTE = 59;

    /**
     * Minimum second
     *
     * @var int
     */
    protected const MIN_SECOND = 0;

    /**
     * Maximum second
     *
     * @var int
     */
    protected const MAX_SECOND = 59;

    /**
     * Minimum microsecond
     *
     * @var int
     */
    protected const MIN_MICROSECOND = 0;

    /**
     * Maximum microsecond
     *
     * @var int
     */
    protected const MAX_MICROSECOND = 999999;

    /**
     * Hour
     *
     * @var int
     */
    protected $hour;

    /**
     * Minute
     *
     * @var int
     */
    protected $minute;

    /**
     * Second
     *
     * @var int
     */
    protected $second;

    /**
     * Microsecond
     *
     * @var int
     */
    protected $microsecond;

    /**
     * Constructs Time
     *
     * @param int $hour        The hour
     * @param int $minute      The minute
     * @param int $second      The second
     * @param int $microsecond The microsecond
     *
     * @throws DomainException When the time is not valid
     */
    public function __construct(int $hour, int $minute, int $second, int $microsecond = 0)
    {
        $this->guardTime($hour, $minute, $second, $microsecond);
        $this->hour = $hour;
        $this->minute = $minute;
        $this->second = $second;
        $this->microsecond = $microsecond;
    }

    /**
     * Creates instance from time values
     *
     * @param int $hour        The hour
     * @param int $minute      The minute
     * @param int $second      The second
     * @param int $microsecond The microsecond
     *
     * @return Time
     *
     * @throws DomainException When the time is not valid
     */
    public static function create(int $hour, int $minute, int $second, int $microsecond = 0): Time
    {
        return new static($hour, $minute, $second, $microsecond);
    }

    /**
     * Creates instance for the current time
     *
     * @param string|null $timezone The timezone string or null for default
     *
     * @return Time
     *
     * @throws AssertionException When the timezone is not valid
     */
    public static function now(?string $timezone = null): Time
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
     *
     * @param DateTimeInterface $dateTime A DateTimeInterface instance
     *
     * @return Time
     */
    public static function fromNative(DateTimeInterface $dateTime): Time
    {
        $hour = (int) $dateTime->format('G');
        $minute = (int) $dateTime->format('i');
        $second = (int) $dateTime->format('s');
        $microsecond = (int) $dateTime->format('u');

        return new static($hour, $minute, $second, $microsecond);
    }

    /**
     * Creates instance from a timestamp and timezone
     *
     * @param int         $timestamp The timestamp
     * @param string|null $timezone  The timezone string or null for default
     *
     * @return Time
     *
     * @throws AssertionException When the timezone is not valid
     */
    public static function fromTimestamp(int $timestamp, ?string $timezone = null): Time
    {
        $timezone = $timezone ?: date_default_timezone_get();
        Assert::isTimezone($timezone);

        $time = sprintf('%d', $timestamp);
        $dateTime = DateTimeImmutable::createFromFormat('U', $time, new DateTimeZone('UTC'));
        $dateTime = $dateTime->setTimezone(new DateTimeZone($timezone));
        $hour = (int) $dateTime->format('G');
        $minute = (int) $dateTime->format('i');
        $second = (int) $dateTime->format('s');
        $microsecond = (int) $dateTime->format('u');

        return new static($hour, $minute, $second, $microsecond);
    }

    /**
     * {@inheritdoc}
     */
    public static function fromString(string $value): Time
    {
        $pattern = '/\A(?P<hour>[\d]{2}):(?P<minute>[\d]{2}):(?P<second>[\d]{2})\.(?P<microsecond>[\d]{6})\z/';
        if (!preg_match($pattern, $value, $matches)) {
            $message = sprintf('%s expects $value in "H:i:s.u" format', __METHOD__);
            throw new DomainException($message);
        }

        $hour = (int) $matches['hour'];
        $minute = (int) $matches['minute'];
        $second = (int) $matches['second'];
        $microsecond = (int) $matches['microsecond'];

        return new static($hour, $minute, $second, $microsecond);
    }

    /**
     * Retrieves the hour
     *
     * @return int
     */
    public function hour(): int
    {
        return $this->hour;
    }

    /**
     * Retrieves the minute
     *
     * @return int
     */
    public function minute(): int
    {
        return $this->minute;
    }

    /**
     * Retrieves the second
     *
     * @return int
     */
    public function second(): int
    {
        return $this->second;
    }

    /**
     * Retrieves the microsecond
     *
     * @return int
     */
    public function microsecond(): int
    {
        return $this->microsecond;
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        return sprintf('%02d:%02d:%02d.%06d', $this->hour, $this->minute, $this->second, $this->microsecond);
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
     * @param int $hour        The hour
     * @param int $minute      The minute
     * @param int $second      The second
     * @param int $microsecond The microsecond
     *
     * @return void
     *
     * @throws DomainException When the time is not valid
     */
    protected function guardTime(int $hour, int $minute, int $second, int $microsecond): void
    {
        if ($hour < static::MIN_HOUR || $hour > static::MAX_HOUR) {
            $message = sprintf('Hour (%d) out of range[%d, %d]', $hour, static::MIN_HOUR, static::MAX_HOUR);
            throw new DomainException($message);
        }

        if ($minute < static::MIN_MINUTE || $minute > static::MAX_MINUTE) {
            $message = sprintf('Minute (%d) out of range[%d, %d]', $minute, static::MIN_MINUTE, static::MAX_MINUTE);
            throw new DomainException($message);
        }

        if ($second < static::MIN_SECOND || $second > static::MAX_SECOND) {
            $message = sprintf('Second (%d) out of range[%d, %d]', $second, static::MIN_SECOND, static::MAX_SECOND);
            throw new DomainException($message);
        }

        if ($microsecond < static::MIN_MICROSECOND || $microsecond > static::MAX_MICROSECOND) {
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
