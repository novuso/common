<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Messaging\Event;

use Exception;

/**
 * EventDispatcherInterface is the interface for an event dispatcher
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface EventDispatcherInterface
{
    /**
     * Triggers an event
     *
     * The dispatcher should wrap the event in an event message, then dispatch
     *
     * @param EventInterface $event The event
     *
     * @return void
     *
     * @throws Exception When an error occurs
     */
    public function trigger(EventInterface $event): void;

    /**
     * Dispatches an event message
     *
     * @param EventMessage $message The event message
     *
     * @return void
     *
     * @throws Exception When an error occurs
     */
    public function dispatch(EventMessage $message): void;

    /**
     * Registers a subscriber to handle events
     *
     * @param EventSubscriberInterface $subscriber The event subscriber
     *
     * @return void
     */
    public function register(EventSubscriberInterface $subscriber): void;

    /**
     * Unregisters a subscriber from handling events
     *
     * @param EventSubscriberInterface $subscriber The event subscriber
     *
     * @return void
     */
    public function unregister(EventSubscriberInterface $subscriber): void;

    /**
     * Adds a handler for a specific event
     *
     * @param string   $eventType The event type
     * @param callable $handler   The event handler
     * @param int      $priority  Higher priority handlers are called first
     *
     * @return void
     */
    public function addHandler(string $eventType, callable $handler, int $priority = 0): void;

    /**
     * Retrieves handlers for an event or all events
     *
     * @param string|null $eventType The event type; null for all events
     *
     * @return array
     */
    public function getHandlers(?string $eventType = null): array;

    /**
     * Checks if handlers are registered for an event or any event
     *
     * @param string|null $eventType The event type; null for all events
     *
     * @return bool
     */
    public function hasHandlers(?string $eventType = null): bool;

    /**
     * Removes a handler from a specified event
     *
     * @param string   $eventType The event type
     * @param callable $handler   The event handler
     *
     * @return void
     */
    public function removeHandler(string $eventType, callable $handler): void;
}
