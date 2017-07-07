<?php declare(strict_types=1);

namespace Novuso\Common\Domain\DateTime;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Novuso\Common\Domain\Type\ValueObject;
use Novuso\System\Exception\DomainException;
use Novuso\System\Type\Comparable;
use Novuso\System\Utility\Validate;
use function Novuso\Common\Functions\same_type;

/**
 * Time represents a time of day
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class Time extends ValueObject implements Comparable
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
     * Constructs Time
     *
     * @param int $hour   The hour
     * @param int $minute The minute
     * @param int $second The second
     *
     * @throws DomainException When the time is not valid
     */
    public function __construct(int $hour, int $minute, int $second)
    {
        $this->guardTime($hour, $minute, $second);
        $this->hour = $hour;
        $this->minute = $minute;
        $this->second = $second;
    }

    /**
     * Creates instance from time values
     *
     * @param int $hour   The hour
     * @param int $minute The minute
     * @param int $second The second
     *
     * @return Time
     *
     * @throws DomainException When the time is not valid
     */
    public static function create(int $hour, int $minute, int $second): Time
    {
        return new static($hour, $minute, $second);
    }

    /**
     * Creates instance for the current time
     *
     * @param string|null $timezone The timezone string or null for default
     *
     * @return Time
     */
    public static function now(?string $timezone = null): Time
    {
        $timezone = $timezone ?: date_default_timezone_get();
        assert(Validate::isTimezone($timezone), sprintf('Invalid timezone: %s', $timezone));

        $dateTime = new DateTimeImmutable('now', new DateTimeZone($timezone));
        $hour = (int) $dateTime->format('G');
        $minute = (int) $dateTime->format('i');
        $second = (int) $dateTime->format('s');

        return new static($hour, $minute, $second);
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

        return new static($hour, $minute, $second);
    }

    /**
     * Creates instance from a timestamp and timezone
     *
     * @param int         $timestamp The timestamp
     * @param string|null $timezone  The timezone string or null for default
     *
     * @return Time
     */
    public static function fromTimestamp(int $timestamp, ?string $timezone = null): Time
    {
        $timezone = $timezone ?: date_default_timezone_get();
        assert(Validate::isTimezone($timezone), sprintf('Invalid timezone: %s', $timezone));

        $time = sprintf('%d', $timestamp);
        $dateTime = DateTimeImmutable::createFromFormat('U', $time, new DateTimeZone('UTC'));
        $dateTime = $dateTime->setTimezone(new DateTimeZone($timezone));
        $hour = (int) $dateTime->format('G');
        $minute = (int) $dateTime->format('i');
        $second = (int) $dateTime->format('s');

        return new static($hour, $minute, $second);
    }

    /**
     * Creates instance from time string
     *
     * @param string $value The time string
     *
     * @return Time
     *
     * @throws DomainException When the time is not formatted correctly
     * @throws DomainException When the time is not valid
     */
    public static function fromString(string $value): Time
    {
        $pattern = '/\A(?P<hour>[\d]{2}):(?P<minute>[\d]{2}):(?P<second>[\d]{2})\z/';
        if (!preg_match($pattern, $value, $matches)) {
            $message = sprintf('%s expects $value in "H:i:s" format', __METHOD__);
            throw new DomainException($message);
        }

        $hour = (int) $matches['hour'];
        $minute = (int) $matches['minute'];
        $second = (int) $matches['second'];

        return new static($hour, $minute, $second);
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
     * {@inheritdoc}
     */
    public function toString(): string
    {
        return sprintf('%02d:%02d:%02d', $this->hour, $this->minute, $this->second);
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
            same_type($this, $object),
            sprintf('Comparison requires instance of %s', static::class)
        );

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

        return 0;
    }

    /**
     * Validates the time
     *
     * @param int $hour   The hour
     * @param int $minute The minute
     * @param int $second The second
     *
     * @return void
     *
     * @throws DomainException When the time is not valid
     */
    protected function guardTime(int $hour, int $minute, int $second): void
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
    }
}
