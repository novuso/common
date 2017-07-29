<?php declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Event;

use Novuso\Common\Application\Messaging\MessageQueueInterface;
use Novuso\Common\Domain\Messaging\Event\EventDispatcherInterface;
use Novuso\Common\Domain\Messaging\Event\EventInterface;
use Novuso\Common\Domain\Messaging\Event\EventMessage;
use Novuso\Common\Domain\Messaging\Event\EventSubscriberInterface;

/**
 * QueueingEventDispatcher dispatches events to a message queue
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class QueueingEventDispatcher implements EventDispatcherInterface
{
    /**
     * Message queue
     *
     * @var MessageQueueInterface
     */
    protected $messageQueue;

    /**
     * Topic name
     *
     * @var string
     */
    protected $topicName;

    /**
     * Constructs QueueingEventDispatcher
     *
     * @param MessageQueueInterface $messageQueue The message queue
     * @param string                $topicName    The topic name
     */
    public function __construct(MessageQueueInterface $messageQueue, string $topicName)
    {
        $this->messageQueue = $messageQueue;
        $this->topicName = $topicName;
    }

    /**
     * {@inheritdoc}
     */
    public function trigger(EventInterface $event): void
    {
        $this->dispatch(EventMessage::create($event));
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(EventMessage $message): void
    {
        $this->messageQueue->enqueue($this->topicName, $message);
    }

    /**
     * {@inheritdoc}
     */
    public function register(EventSubscriberInterface $subscriber): void
    {
        // no operation
    }

    /**
     * {@inheritdoc}
     */
    public function unregister(EventSubscriberInterface $subscriber): void
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
