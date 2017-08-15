<?php declare(strict_types=1);

namespace Novuso\Common\Domain\EventSourcing;

use Novuso\Common\Domain\Messaging\Event\EventInterface;
use Novuso\Common\Domain\Model\AggregateRoot;
use Novuso\Common\Domain\Model\EventRecord;
use Novuso\System\Utility\ClassName;
use ReflectionClass;
use Traversable;

/**
 * EventSourcedAggregateRoot is the base class for an event sourced aggregate root
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
abstract class EventSourcedAggregateRoot extends AggregateRoot
{
    /**
     * Creates instance from an event stream history
     *
     * Override to customize instantiation without using reflection.
     *
     * @param iterable $eventRecords The event records
     *
     * @return EventSourcedAggregateRoot
     */
    public static function reconstitute(iterable $eventRecords)
    {
        $reflection = new ReflectionClass(static::class);

        /** @var EventSourcedAggregateRoot $aggregate */
        $aggregate = $reflection->newInstanceWithoutConstructor();

        $lastSequence = null;
        /** @var EventRecord $eventRecord */
        foreach ($eventRecords as $eventRecord) {
            $lastSequence = $eventRecord->sequenceNumber();
            /** @var EventInterface $event */
            $event = $eventRecord->eventMessage()->payload();
            $aggregate->handleRecursively($event);
        }

        $aggregate->initializeCommittedVersion($lastSequence);

        return $aggregate;
    }

    /**
     * Records a domain event
     *
     * @param EventInterface $event The domain event
     *
     * @return void
     */
    public function recordThat(EventInterface $event): void
    {
        $this->handleRecursively($event);
        parent::recordThat($event);
    }

    /**
     * Handles the domain event recursively
     *
     * @param EventInterface $event The domain event
     *
     * @return void
     */
    protected function handleRecursively(EventInterface $event): void
    {
        $this->handle($event);

        foreach ($this->childEntities() as $entity) {
            $entity->registerAggregateRoot($this);
            $entity->handleRecursively($event);
        }
    }

    /**
     * Handles event if the apply method is available
     *
     * This method delegates to a protected method based on the domain event
     * class name: 'apply'.$className
     *
     * @param EventInterface $event The domain event
     *
     * @return void
     */
    protected function handle(EventInterface $event): void
    {
        $method = 'apply'.ClassName::short($event);

        if (!method_exists($this, $method)) {
            return;
        }

        $this->$method($event);
    }

    /**
     * Retrieves all child entities
     *
     * @return Traversable|EventSourcedEntity[]
     */
    protected function childEntities()
    {
        return [];
    }
}
