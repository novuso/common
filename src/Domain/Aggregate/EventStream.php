<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Aggregate;

use Countable;
use IteratorAggregate;
use Novuso\Common\Domain\Identification\Identifier;
use Novuso\System\Collection\SortedSet;
use Novuso\System\Type\Type;
use Traversable;

/**
 * EventStream is a read stream of event records for a single aggregate
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class EventStream implements Countable, IteratorAggregate
{
    /**
     * Aggregate ID
     *
     * @var Identifier
     */
    protected $aggregateId;

    /**
     * Aggregate type
     *
     * @var Type
     */
    protected $aggregateType;

    /**
     * Committed version
     *
     * @var int|null
     */
    protected $committedVersion;

    /**
     * Current version
     *
     * @var int|null
     */
    protected $currentVersion;

    /**
     * Event records
     *
     * @var SortedSet
     */
    protected $eventRecords;

    /**
     * Constructs EventStream
     *
     * @param Identifier $aggregateId      The aggregate ID
     * @param Type       $aggregateType    The aggregate type
     * @param int|null   $committedVersion The committed version
     * @param int|null   $currentVersion   The current version
     * @param iterable   $eventRecords     The event records
     */
    public function __construct(
        Identifier $aggregateId,
        Type $aggregateType,
        ?int $committedVersion,
        ?int $currentVersion,
        iterable $eventRecords
    ) {
        $this->aggregateId = $aggregateId;
        $this->aggregateType = $aggregateType;
        $this->committedVersion = $committedVersion;
        $this->currentVersion = $currentVersion;
        $this->eventRecords = SortedSet::comparable(EventRecord::class);
        foreach ($eventRecords as $eventRecord) {
            $this->eventRecords->add($eventRecord);
        }
    }

    /**
     * Checks if empty
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->eventRecords->isEmpty();
    }

    /**
     * Retrieves the count
     *
     * @return int
     */
    public function count(): int
    {
        return $this->eventRecords->count();
    }

    /**
     * Retrieves the aggregate ID
     *
     * @return Identifier
     */
    public function aggregateId(): Identifier
    {
        return $this->aggregateId;
    }

    /**
     * Retrieves the aggregate type
     *
     * @return Type
     */
    public function aggregateType(): Type
    {
        return $this->aggregateType;
    }

    /**
     * Retrieves the committed version
     *
     * @return int|null
     */
    public function committedVersion(): ?int
    {
        return $this->committedVersion;
    }

    /**
     * Retrieves the current version
     *
     * @return int|null
     */
    public function currentVersion(): ?int
    {
        return $this->currentVersion;
    }

    /**
     * Retrieves an external iterator
     *
     * @return Traversable|EventRecord[]
     */
    public function getIterator()
    {
        return $this->eventRecords->getIterator();
    }
}
