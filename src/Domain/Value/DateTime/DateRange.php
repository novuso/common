<?php

declare(strict_types=1);

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
     * Constructs DateRange
     *
     * The step may be positive or negative, but cannot be zero.
     *
     * @throws DomainException When the arguments are invalid
     */
    public function __construct(
        protected Date $start,
        protected Date $end,
        protected int $step = 1
    ) {
        if ($this->step === 0) {
            throw new DomainException('Step cannot be zero');
        }

        if ($this->step > 0 && $this->start->isAfter($this->end)) {
            throw new DomainException(
                'End date must be after start date with a positive step'
            );
        }

        if ($this->step < 0 && $this->start->isBefore($this->end)) {
            throw new DomainException(
                'Start date must be before end date with a negative step'
            );
        }
    }

    /**
     * Creates instance from a start date, end date, and interval step
     *
     * The step may be positive or negative, but cannot be zero.
     *
     * @throws DomainException When the arguments are invalid
     */
    public static function create(Date $start, Date $end, int $step = 1): static
    {
        return new static($start, $end, $step);
    }

    /**
     * Creates instance from a start date, interval step, and iterations
     *
     * @throws DomainException When the arguments are invalid
     */
    public static function fromIterations(
        Date $start,
        int $step,
        int $iterations
    ): static {
        if ($step === 0) {
            throw new DomainException('Step cannot be zero');
        }

        $current = new NativeDateTime($start->toString());
        if ($step > 0) {
            // forward iteration
            $interval = DateInterval::createFromDateString(
                sprintf('+%d days', $step)
            );
        } else {
            // reverse iteration
            $interval = DateInterval::createFromDateString(
                sprintf('%d days', $step)
            );
        }

        for ($i = 1; $i < $iterations; $i++) {
            $current->add($interval);
        }

        $end = Date::fromString($current->format('Y-m-d'));

        return new static($start, $end, $step);
    }

    /**
     * @inheritDoc
     */
    public static function fromString(string $value): static
    {
        $pattern = sprintf('/\A%s\z/', implode('', [
            '(?P<start_year>[\d]{4})-(?P<start_month>[\d]{2})-(?P<start_day>[\d]{2})',
            '\.\.',
            '(?P<end_year>[\d]{4})-(?P<end_month>[\d]{2})-(?P<end_day>[\d]{2})',
            '\{(?P<step>-?[\d]+)\}'
        ]));
        if (!preg_match($pattern, $value, $matches)) {
            $message = sprintf(
                '%s expects $value in "Y-m-d..Y-m-d{n}" format',
                __METHOD__
            );
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
     */
    public function start(): Date
    {
        return $this->start;
    }

    /**
     * Retrieves the end date
     */
    public function end(): Date
    {
        return $this->end;
    }

    /**
     * Retrieves the step
     */
    public function step(): int
    {
        return $this->step;
    }

    /**
     * @inheritDoc
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
     */
    public function getIterator(): Traversable
    {
        return $this->createIterator();
    }

    /**
     * Creates iterator for the range
     */
    protected function createIterator(): Generator
    {
        $current = new NativeDateTime($this->start->toString());
        if ($this->step > 0) {
            // forward iteration
            yield Date::fromString($current->format('Y-m-d'));
            $interval = DateInterval::createFromDateString(
                sprintf('+%d days', $this->step)
            );
            while ($current->format('Y-m-d') < $this->end->toString()) {
                $current->add($interval);
                if ($current->format('Y-m-d') <= $this->end->toString()) {
                    yield Date::fromString($current->format('Y-m-d'));
                }
            }
        } else {
            // reverse iteration
            yield Date::fromString($current->format('Y-m-d'));
            $interval = DateInterval::createFromDateString(
                sprintf('%d days', $this->step)
            );
            while ($current->format('Y-m-d') > $this->end->toString()) {
                $current->add($interval);
                if ($current->format('Y-m-d') >= $this->end->toString()) {
                    yield Date::fromString($current->format('Y-m-d'));
                }
            }
        }
    }
}
