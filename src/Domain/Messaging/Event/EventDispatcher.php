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
     * @throws Throwable When an error occurs
     */
    public function trigger(Event $event): void;

    /**
     * Dispatches an event message
     *
     * @throws Throwable When an error occurs
     */
    public function dispatch(EventMessage $message): void;

    /**
     * Registers a subscriber to handle events
     */
    public function register(EventSubscriber $subscriber): void;

    /**
     * Unregisters a subscriber from handling events
     */
    public function unregister(EventSubscriber $subscriber): void;

    /**
     * Adds a handler for a specific event
     */
    public function addHandler(string $eventType, callable $handler, int $priority = 0): void;

    /**
     * Retrieves handlers for an event or all events
     */
    public function getHandlers(?string $eventType = null): array;

    /**
     * Checks if handlers are registered for an event or any event
     */
    public function hasHandlers(?string $eventType = null): bool;

    /**
     * Removes a handler from a specified event
     */
    public function removeHandler(string $eventType, callable $handler): void;
}
