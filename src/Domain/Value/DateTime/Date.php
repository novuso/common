<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Value\DateTime;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Novuso\Common\Domain\Type\ValueObject;
use Novuso\System\Exception\DomainException;
use Novuso\System\Type\Comparable;
use Novuso\System\Utility\Validate;

/**
 * Date represents a calendar date
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class Date extends ValueObject implements Comparable
{
    /**
     * Year
     *
     * @var int
     */
    protected $year;

    /**
     * Month
     *
     * @var int
     */
    protected $month;

    /**
     * Day
     *
     * @var int
     */
    protected $day;

    /**
     * Constructs Date
     *
     * @param int $year  The year
     * @param int $month The month
     * @param int $day   The day
     *
     * @throws DomainException When the date is not valid
     */
    public function __construct(int $year, int $month, int $day)
    {
        $this->guardDate($year, $month, $day);
        $this->year = $year;
        $this->month = $month;
        $this->day = $day;
    }

    /**
     * Creates instance from date values
     *
     * @param int $year  The year
     * @param int $month The month
     * @param int $day   The day
     *
     * @return Date
     *
     * @throws DomainException When the date is not valid
     */
    public static function create(int $year, int $month, int $day): Date
    {
        return new static($year, $month, $day);
    }

    /**
     * Creates instance for the current date
     *
     * @param string|null $timezone The timezone string or null for default
     *
     * @return Date
     */
    public static function now(?string $timezone = null): Date
    {
        $timezone = $timezone ?: date_default_timezone_get();
        assert(Validate::isTimezone($timezone), sprintf('Invalid timezone: %s', $timezone));

        $dateTime = new DateTimeImmutable('now', new DateTimeZone($timezone));
        $year = (int) $dateTime->format('Y');
        $month = (int) $dateTime->format('n');
        $day = (int) $dateTime->format('j');

        return new static($year, $month, $day);
    }

    /**
     * Creates instance from a native DateTime
     *
     * @param DateTimeInterface $dateTime A DateTimeInterface instance
     *
     * @return Date
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
     *
     * @param int         $timestamp The timestamp
     * @param string|null $timezone  The timezone string or null for default
     *
     * @return Date
     */
    public static function fromTimestamp(int $timestamp, ?string $timezone = null): Date
    {
        $timezone = $timezone ?: date_default_timezone_get();
        assert(Validate::isTimezone($timezone), sprintf('Invalid timezone: %s', $timezone));

        $time = sprintf('%d', $timestamp);
        $dateTime = DateTimeImmutable::createFromFormat('U', $time, new DateTimeZone('UTC'));
        $dateTime = $dateTime->setTimezone(new DateTimeZone($timezone));
        $year = (int) $dateTime->format('Y');
        $month = (int) $dateTime->format('n');
        $day = (int) $dateTime->format('j');

        return new static($year, $month, $day);
    }

    /**
     * {@inheritdoc}
     */
    public static function fromString(string $value): Date
    {
        $pattern = '/\A(?P<year>[\d]{4})-(?P<month>[\d]{2})-(?P<day>[\d]{2})\z/';
        if (!preg_match($pattern, $value, $matches)) {
            $message = sprintf('%s expects $value in "Y-m-d" format', __METHOD__);
            throw new DomainException($message);
        }

        $year = (int) $matches['year'];
        $month = (int) $matches['month'];
        $day = (int) $matches['day'];

        return new static($year, $month, $day);
    }

    /**
     * Retrieves the year
     *
     * @return int
     */
    public function year(): int
    {
        return $this->year;
    }

    /**
     * Retrieves the month
     *
     * @return int
     */
    public function month(): int
    {
        return $this->month;
    }

    /**
     * Retrieves the day
     *
     * @return int
     */
    public function day(): int
    {
        return $this->day;
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        return sprintf('%04d-%02d-%02d', $this->year, $this->month, $this->day);
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
            Validate::areSameType($this, $object),
            sprintf('Comparison requires instance of %s', static::class)
        );

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
     * @param int $year  The year
     * @param int $month The month
     * @param int $day   The day
     *
     * @return void
     *
     * @throws DomainException When the date is not valid
     */
    protected function guardDate(int $year, int $month, int $day): void
    {
        if (!checkdate($month, $day, $year)) {
            $message = sprintf('Invalid date: %04d-%02d-%02d', $year, $month, $day);
            throw new DomainException($message);
        }
    }
}
