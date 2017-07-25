<?php declare(strict_types=1);

namespace Novuso\Common\Domain\EventSourcing;

use Novuso\Common\Domain\Messaging\Event\EventInterface;
use Novuso\Common\Domain\Messaging\Event\EventMessage;
use Novuso\System\Exception\OperationException;
use Novuso\System\Type\Type;
use Traversable;

/**
 * AggregateRoot is the base class for an aggregate root entity
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
abstract class AggregateRoot extends Entity
{
    /**
     * Event collection
     *
     * @var EventCollection|null
     */
    protected $eventCollection;

    /**
     * Committed version
     *
     * @var int|null
     */
    protected $committedVersion;

    /**
     * Records a domain event
     *
     * @param EventInterface $event The domain event
     *
     * @return void
     */
    public function recordThat(EventInterface $event): void
    {
        $this->eventCollection()->record(EventMessage::create($event));
    }

    /**
     * Checks if there are recorded events
     *
     * @return bool
     */
    public function hasRecordedEvents(): bool
    {
        return !$this->eventCollection()->isEmpty();
    }

    /**
     * Retrieves recorded events without removal
     *
     * @return Traversable|EventRecord[]
     */
    public function peekAtRecordedEvents()
    {
        return $this->eventCollection()->stream();
    }

    /**
     * Removes and returns recorded events
     *
     * @return Traversable|EventRecord[]
     */
    public function extractRecordedEvents()
    {
        $eventCollection = $this->eventCollection();
        $eventStream = $eventCollection->stream();
        $eventCollection->commit();
        $this->committedVersion = $eventCollection->committedSequence();

        return $eventStream;
    }

    /**
     * Retrieves the committed version
     *
     * @return int|null
     */
    public function committedVersion(): ?int
    {
        if ($this->committedVersion === null) {
            $this->committedVersion = $this->eventCollection()->committedSequence();
        }

        return $this->committedVersion;
    }

    /**
     * Initializes the committed version
     *
     * @param int $committedVersion the initial version
     *
     * @return void
     *
     * @throws OperationException When called with recorded events
     */
    protected function initializeCommittedVersion(int $committedVersion): void
    {
        if (!$this->eventCollection()->isEmpty()) {
            $message = 'Cannot initialize version after recording events';
            throw new OperationException($message);
        }

        $this->eventCollection()->initializeSequence($committedVersion);
    }

    /**
     * Retrieves the event collection
     *
     * @return EventCollection
     */
    protected function eventCollection(): EventCollection
    {
        if ($this->eventCollection === null) {
            $this->eventCollection = new EventCollection($this->id(), Type::create($this));
        }

        return $this->eventCollection;
    }
}
