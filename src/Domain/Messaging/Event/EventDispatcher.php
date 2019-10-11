<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Messaging\Event;

use Throwable;

/**
 * Interface EventDispatcher
 */
interface EventDispatcher
{
    /**
     * Triggers an event
     *
     * The dispatcher should wrap the event in an event message, then dispatch
     *
     * @param Event $event The event
     *
     * @return void
     *
     * @throws Throwable When an error occurs
     */
    public function trigger(Event $event): void;

    /**
     * Dispatches an event message
     *
     * @param EventMessage $message The event message
     *
     * @return void
     *
     * @throws Throwable When an error occurs
     */
    public function dispatch(EventMessage $message): void;

    /**
     * Registers a subscriber to handle events
     *
     * @param EventSubscriber $subscriber The event subscriber
     *
     * @return void
     */
    public function register(EventSubscriber $subscriber): void;

    /**
     * Unregisters a subscriber from handling events
     *
     * @param EventSubscriber $subscriber The event subscriber
     *
     * @return void
     */
    public function unregister(EventSubscriber $subscriber): void;

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
