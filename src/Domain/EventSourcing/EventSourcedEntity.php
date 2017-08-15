<?php declare(strict_types=1);

namespace Novuso\Common\Domain\EventSourcing;

use Novuso\Common\Domain\Messaging\Event\EventInterface;
use Novuso\Common\Domain\Model\Entity;
use Novuso\System\Exception\DomainException;
use Novuso\System\Utility\ClassName;
use Traversable;

/**
 * EventSourcedEntity is the base class for an event sourced entity
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
abstract class EventSourcedEntity extends Entity
{
    /**
     * Aggregate Root
     *
     * @var EventSourcedAggregateRoot|null
     */
    protected $aggregateRoot;

    /**
     * Registers the aggregate root
     *
     * @internal
     *
     * @param EventSourcedAggregateRoot $aggregateRoot The aggregate root
     *
     * @return void
     *
     * @throws DomainException
     */
    public function registerAggregateRoot(EventSourcedAggregateRoot $aggregateRoot): void
    {
        if ($this->aggregateRoot !== null && $this->aggregateRoot !== $aggregateRoot) {
            throw new DomainException('Aggregate root already registered');
        }

        $this->aggregateRoot = $aggregateRoot;
    }

    /**
     * Handles the domain event recursively
     *
     * @internal
     *
     * @param EventInterface $event The domain event
     *
     * @return void
     */
    public function handleRecursively(EventInterface $event): void
    {
        $this->handle($event);

        foreach ($this->childEntities() as $entity) {
            $entity->registerAggregateRoot($this->getAggregateRoot());
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
     * Retrieves the aggregate root
     *
     * @return EventSourcedAggregateRoot
     *
     * @throws DomainException
     */
    protected function getAggregateRoot(): EventSourcedAggregateRoot
    {
        if ($this->aggregateRoot === null) {
            throw new DomainException('Aggregate root is not registered');
        }

        return $this->aggregateRoot;
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
