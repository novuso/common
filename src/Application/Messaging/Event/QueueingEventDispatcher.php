<?php declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Event;

use Novuso\Common\Application\Messaging\MessageQueue;
use Novuso\Common\Domain\Messaging\Event\EventDispatcher;
use Novuso\Common\Domain\Messaging\Event\Event;
use Novuso\Common\Domain\Messaging\Event\EventMessage;
use Novuso\Common\Domain\Messaging\Event\EventSubscriber;

/**
 * QueueingEventDispatcher dispatches events to a message queue
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class QueueingEventDispatcher implements EventDispatcher
{
    /**
     * Message queue
     *
     * @var MessageQueue
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
     * @param MessageQueue $messageQueue The message queue
     * @param string       $topicName    The topic name
     */
    public function __construct(MessageQueue $messageQueue, string $topicName)
    {
        $this->messageQueue = $messageQueue;
        $this->topicName = $topicName;
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
        $this->messageQueue->enqueue($this->topicName, $message);
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
