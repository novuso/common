<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Model;

use Countable;
use Novuso\Common\Domain\Identity\Identifier;
use Novuso\Common\Domain\Messaging\Event\EventMessage;
use Novuso\System\Collection\Api\OrderedSet;
use Novuso\System\Collection\SortedSet;
use Novuso\System\Type\Type;
use Traversable;

/**
 * EventCollection is a collection of events on an aggregate root
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class EventCollection implements Countable
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
     * Committed sequence number
     *
     * @var int|null
     */
    protected $committedSequence;

    /**
     * Last sequence number
     *
     * @var int|null
     */
    protected $lastSequence;

    /**
     * Event records
     *
     * @var OrderedSet
     */
    protected $eventRecords;

    /**
     * Constructs EventCollection
     *
     * @param Identifier $aggregateId   The aggregate ID
     * @param Type       $aggregateType The aggregate type
     */
    public function __construct(Identifier $aggregateId, Type $aggregateType)
    {
        $this->aggregateId = $aggregateId;
        $this->aggregateType = $aggregateType;
        $this->eventRecords = SortedSet::comparable(EventRecord::class);
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
     * Records an event message
     *
     * @param EventMessage $message The event message
     *
     * @return void
     */
    public function record(EventMessage $message): void
    {
        $eventRecord = new EventRecord(
            $message,
            $this->aggregateId,
            $this->aggregateType,
            $this->nextSequence()
        );
        $this->lastSequence = $eventRecord->sequenceNumber();
        $this->eventRecords->add($eventRecord);
    }

    /**
     * Retrieves a stream of recorded events
     *
     * @return Traversable|EventRecord[]
     */
    public function stream()
    {
        return $this->eventRecords;
    }

    /**
     * Initializes the committed sequence
     *
     * @param int $committedSequence The committed sequence number
     *
     * @return void
     */
    public function initializeSequence(int $committedSequence): void
    {
        assert($this->isEmpty(), 'Cannot initialize sequence after adding events');
        $this->committedSequence = $committedSequence;
    }

    /**
     * Retrieves the committed sequence
     *
     * @return int|null
     */
    public function committedSequence(): ?int
    {
        return $this->committedSequence;
    }

    /**
     * Retrieves the last sequence number
     *
     * @return int|null
     */
    public function lastSequence(): ?int
    {
        if ($this->isEmpty()) {
            return $this->committedSequence;
        }

        return $this->lastSequence;
    }

    /**
     * Clears events and updates committed sequence number
     *
     * @return void
     */
    public function commit(): void
    {
        $this->committedSequence = $this->lastSequence();
        $this->eventRecords = SortedSet::comparable(EventRecord::class);
    }

    /**
     * Retrieves the next sequence number
     *
     * @return int
     */
    protected function nextSequence(): int
    {
        $sequence = $this->lastSequence();

        if ($sequence === null) {
            return 0;
        }

        return $sequence + 1;
    }
}
