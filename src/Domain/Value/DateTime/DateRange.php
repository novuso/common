<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Value\DateTime;

use DateInterval;
use DateTime as NativeDateTime;
use Generator;
use IteratorAggregate;
use Novuso\Common\Domain\Type\ValueObject;
use Novuso\System\Exception\DomainException;
use Traversable;

/**
 * Class DateRange
 */
final class DateRange extends ValueObject implements IteratorAggregate
{
    /**
     * Start date
     *
     * @var Date
     */
    protected $start;

    /**
     * End date
     *
     * @var Date
     */
    protected $end;

    /**
     * Interval step
     *
     * @var int
     */
    protected $step;

    /**
     * Constructs DateRange
     *
     * The step may be positive or negative, but cannot be zero.
     *
     * @param Date $start The start date
     * @param Date $end   The end date
     * @param int  $step  The interval step
     *
     * @throws DomainException When the arguments are invalid
     */
    public function __construct(Date $start, Date $end, int $step = 1)
    {
        if ($step === 0) {
            throw new DomainException('Step cannot be zero');
        }

        if ($step > 0 && $start->compareTo($end) !== -1) {
            throw new DomainException('End date must be greater than start date with a positive step');
        }

        if ($step < 0 && $start->compareTo($end) !== 1) {
            throw new DomainException('Start date must be greater than end date with a negative step');
        }

        $this->start = $start;
        $this->end = $end;
        $this->step = $step;
    }

    /**
     * Creates instance from a start date, end date, and interval step
     *
     * The step may be positive or negative, but cannot be zero.
     *
     * @param Date $start The start date
     * @param Date $end   The end date
     * @param int  $step  The interval step
     *
     * @return DateRange
     *
     * @throws DomainException When the arguments are invalid
     */
    public static function create(Date $start, Date $end, int $step = 1): DateRange
    {
        return new static($start, $end, $step);
    }

    /**
     * Creates instance from a start date, interval step, and number of iterations
     *
     * @param Date $start The start date
     * @param int  $step  The interval step
     * @param int  $iterations
     *
     * @return DateRange
     *
     * @throws DomainException When the arguments are invalid
     */
    public static function fromIterations(Date $start, int $step, int $iterations): DateRange
    {
        if ($step === 0) {
            throw new DomainException('Step cannot be zero');
        }

        $current = new NativeDateTime($start->toString());
        if ($step > 0) {
            // forward iteration
            $interval = DateInterval::createFromDateString(sprintf('+%d days', $step));
        } else {
            // reverse iteration
            $interval = DateInterval::createFromDateString(sprintf('%d days', $step));
        }

        for ($i = 1; $i < $iterations; $i++) {
            $current->add($interval);
        }

        $end = Date::fromString($current->format('Y-m-d'));

        return new static($start, $end, $step);
    }

    /**
     * {@inheritdoc}
     */
    public static function fromString(string $value): DateRange
    {
        $pattern = sprintf('/\A%s\z/', implode('', [
            '(?P<start_year>[\d]{4})-(?P<start_month>[\d]{2})-(?P<start_day>[\d]{2})',
            '\.\.',
            '(?P<end_year>[\d]{4})-(?P<end_month>[\d]{2})-(?P<end_day>[\d]{2})',
            '\{(?P<step>-?[\d]+)\}'
        ]));
        if (!preg_match($pattern, $value, $matches)) {
            $message = sprintf('%s expects $value in "Y-m-d..Y-m-d{n}" format', __METHOD__);
            throw new DomainException($message);
        }

        $startYear = (int) $matches['start_year'];
        $startMonth = (int) $matches['start_month'];
        $startDay = (int) $matches['start_day'];
        $endYear = (int) $matches['end_year'];
        $endMonth = (int) $matches['end_month'];
        $endDay = (int) $matches['end_day'];
        $step = (int) $matches['step'];

        return new static(
            Date::create($startYear, $startMonth, $startDay),
            Date::create($endYear, $endMonth, $endDay),
            $step
        );
    }

    /**
     * Retrieves the start date
     *
     * @return Date
     */
    public function start(): Date
    {
        return $this->start;
    }

    /**
     * Retrieves the end date
     *
     * @return Date
     */
    public function end(): Date
    {
        return $this->end;
    }

    /**
     * Retrieves the step
     *
     * @return int
     */
    public function step(): int
    {
        return $this->step;
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        return sprintf(
            '%s..%s{%d}',
            $this->start->toString(),
            $this->end->toString(),
            $this->step
        );
    }

    /**
     * Checks if a date is in the range
     *
     * @param Date $date The date
     *
     * @return bool
     */
    public function contains(Date $date): bool
    {
        foreach ($this->createIterator() as $current) {
            if ($current->equals($date)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Retrieves an iterator
     *
     * @return Traversable|Date[]
     */
    public function getIterator()
    {
        return $this->createIterator();
    }

    /**
     * Creates iterator for the range
     *
     * @return Generator|Date[]
     */
    protected function createIterator(): Generator
    {
        $current = new NativeDateTime($this->start->toString());
        if ($this->step > 0) {
            // forward iteration
            yield Date::fromString($current->format('Y-m-d'));
            $interval = DateInterval::createFromDateString(sprintf('+%d days', $this->step));
            while ($current->format('Y-m-d') < $this->end->toString()) {
                $current->add($interval);
                if ($current->format('Y-m-d') <= $this->end->toString()) {
                    yield Date::fromString($current->format('Y-m-d'));
                }
            }
        } else {
            // reverse iteration
            yield Date::fromString($current->format('Y-m-d'));
            $interval = DateInterval::createFromDateString(sprintf('%d days', $this->step));
            while ($current->format('Y-m-d') > $this->end->toString()) {
                $current->add($interval);
                if ($current->format('Y-m-d') >= $this->end->toString()) {
                    yield Date::fromString($current->format('Y-m-d'));
                }
            }
        }
    }
}
