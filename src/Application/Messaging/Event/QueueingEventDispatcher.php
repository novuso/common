<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Event;

use Novuso\Common\Application\Messaging\MessageQueue;
use Novuso\Common\Domain\Messaging\Event\AsynchronousEventDispatcher;
use Novuso\Common\Domain\Messaging\Event\Event;
use Novuso\Common\Domain\Messaging\Event\EventMessage;
use Novuso\Common\Domain\Messaging\Event\EventSubscriber;

/**
 * Class QueueingEventDispatcher
 */
final class QueueingEventDispatcher implements AsynchronousEventDispatcher
{
    /**
     * Constructs QueueingEventDispatcher
     */
    public function __construct(
        protected MessageQueue $messageQueue,
        protected string $queueName
    ) {
    }

    /**
     * @inheritDoc
     */
    public function trigger(Event $event): void
    {
        $this->dispatch(EventMessage::create($event));
    }

    /**
     * @inheritDoc
     */
    public function dispatch(EventMessage $message): void
    {
        $this->messageQueue->enqueue($this->queueName, $message);
    }

    /**
     * @inheritDoc
     */
    public function register(EventSubscriber $subscriber): void
    {
        // no operation
    }

    /**
     * @inheritDoc
     */
    public function unregister(EventSubscriber $subscriber): void
    {
        // no operation
    }

    /**
     * @inheritDoc
     */
    public function addHandler(
        string $eventType,
        callable $handler,
        int $priority = 0
    ): void {
        // no operation
    }

    /**
     * @inheritDoc
     */
    public function getHandlers(?string $eventType = null): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function hasHandlers(?string $eventType = null): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function removeHandler(string $eventType, callable $handler): void
    {
        // no operation
    }
}
