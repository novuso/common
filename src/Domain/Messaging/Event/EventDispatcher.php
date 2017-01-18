<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Messaging\Event;

use Exception;
use Novuso\Common\Domain\Model\Api\AggregateRoot;

/**
 * EventDispatcher is the interface for an event dispatcher
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface EventDispatcher
{
    /**
     * Dispatches an event
     *
     * @param Event $event The event
     *
     * @return void
     *
     * @throws Exception When an error occurs
     */
    public function dispatch(Event $event);

    /**
     * Dispatches recorded aggregate events
     *
     * @param AggregateRoot $aggregateRoot The aggregate root
     *
     * @return void
     *
     * @throws Exception When an error occurs
     */
    public function dispatchEvents(AggregateRoot $aggregateRoot);

    /**
     * Registers a subscriber to handle events
     *
     * @param EventSubscriber $subscriber The event subscriber
     *
     * @return void
     */
    public function register(EventSubscriber $subscriber);

    /**
     * Unregisters a subscriber from handling events
     *
     * @param EventSubscriber $subscriber The event subscriber
     *
     * @return void
     */
    public function unregister(EventSubscriber $subscriber);

    /**
     * Adds a handler for a specific event
     *
     * @param string   $eventType The event type
     * @param callable $handler   The event handler
     * @param int      $priority  Higher priority handlers are called first
     *
     * @return void
     */
    public function addHandler(string $eventType, callable $handler, int $priority = 0);

    /**
     * Retrieves handlers for an event or all events
     *
     * @param string|null $eventType The event type; null for all events
     *
     * @return array
     */
    public function getHandlers(string $eventType = null): array;

    /**
     * Checks if handlers are registered for an event or any event
     *
     * @param string|null $eventType The event type; null for all events
     *
     * @return bool
     */
    public function hasHandlers(string $eventType = null): bool;

    /**
     * Removes a handler from a specified event
     *
     * @param string   $eventType The event type
     * @param callable $handler   The event handler
     *
     * @return void
     */
    public function removeHandler(string $eventType, callable $handler);
}
