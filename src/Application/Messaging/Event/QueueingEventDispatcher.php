<?php declare(strict_types=1);

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
     * Message queue
     *
     * @var MessageQueue
     */
    protected $messageQueue;

    /**
     * Queue name
     *
     * @var string
     */
    protected $queueName;

    /**
     * Constructs QueueingEventDispatcher
     *
     * @param MessageQueue $messageQueue The message queue
     * @param string       $queueName    The queue name
     */
    public function __construct(MessageQueue $messageQueue, string $queueName)
    {
        $this->messageQueue = $messageQueue;
        $this->queueName = $queueName;
    }

    /**
     * {@inheritdoc}
     */
    public function trigger(Event $event): void
    {
        $this->dispatch(EventMessage::create($event));
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(EventMessage $message): void
    {
        $this->messageQueue->enqueue($this->queueName, $message);
    }

    /**
     * {@inheritdoc}
     */
    public function register(EventSubscriber $subscriber): void
    {
        // no operation
    }

    /**
     * {@inheritdoc}
     */
    public function unregister(EventSubscriber $subscriber): void
    {
        // no operation
    }

    /**
     * {@inheritdoc}
     */
    public function addHandler(string $eventType, callable $handler, int $priority = 0): void
    {
        // no operation
    }

    /**
     * {@inheritdoc}
     */
    public function getHandlers(?string $eventType = null): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function hasHandlers(?string $eventType = null): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function removeHandler(string $eventType, callable $handler): void
    {
        // no operation
    }
}
