<?php declare(strict_types=1);

namespace Novuso\Common\Application\EventSourcing;

use Novuso\Common\Application\EventStore\EventStoreInterface;
use Novuso\Common\Domain\Model\EventRecord;
use Novuso\Common\Domain\Messaging\Event\EventDispatcherInterface;
use Throwable;

/**
 * EventProcessor handles storing and dispatching an event in sequence
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class EventProcessor
{
    /**
     * Event store
     *
     * @var EventStoreInterface
     */
    protected $eventStore;

    /**
     * Event dispatcher
     *
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * Constructs EventProcessor
     *
     * @param EventStoreInterface      $eventStore      The event store
     * @param EventDispatcherInterface $eventDispatcher The event dispatcher
     */
    public function __construct(EventStoreInterface $eventStore, EventDispatcherInterface $eventDispatcher)
    {
        $this->eventStore = $eventStore;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Processes an event record
     *
     * Only dispatches an event if the event store does not throw an exception.
     *
     * @param EventRecord $eventRecord The event record
     *
     * @return void
     *
     * @throws Throwable When an error occurs
     */
    public function process(EventRecord $eventRecord): void
    {
        $this->eventStore->append($eventRecord);
        $this->eventDispatcher->dispatch($eventRecord->eventMessage());
    }
}
