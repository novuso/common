<?php declare(strict_types=1);

namespace Novuso\Common\Application\Messaging;

use Novuso\Common\Application\Messaging\Exception\MessageQueueException;
use Novuso\Common\Domain\Messaging\Message;
use Novuso\Common\Domain\Messaging\MessageId;
use Novuso\Common\Domain\Messaging\MetaData;
use Novuso\System\Exception\LookupException;
use Throwable;

/**
 * Class RecyclingMessageQueue
 */
final class RecyclingMessageQueue implements MessageQueue
{
    /**
     * Message queue
     *
     * @var MessageQueue
     */
    protected $queue;

    /**
     * Message store
     *
     * @var MessageStore
     */
    protected $store;

    /**
     * Constructs RecyclingMessageQueue
     *
     * @param MessageQueue $queue The message queue
     * @param MessageStore $store The message store
     */
    public function __construct(MessageQueue $queue, MessageStore $store)
    {
        $this->queue = $queue;
        $this->store = $store;
    }

    /**
     * Recycles a stored message
     *
     * @param string    $name      The queue name
     * @param MessageId $messageId The message ID
     *
     * @throws MessageQueueException
     */
    public function recycle(string $name, MessageId $messageId): void
    {
        try {
            $message = $this->store->get($name, $messageId);

            if ($message === null) {
                throw new LookupException(sprintf('Message %s not found', $messageId->toString()));
            }

            $this->enqueue($name, $message->withMetaData(MetaData::create()));
            $this->store->remove($name, $messageId);
        } catch (Throwable $e) {
            throw new MessageQueueException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Recycles all stored messages
     *
     * @param string $name The queue name
     *
     * @throws MessageQueueException
     */
    public function recycleAll(string $name): void
    {
        try {
            $messages = $this->store->getAll($name);

            /** @var Message $message */
            foreach ($messages as $message) {
                $this->enqueue($name, $message);
                $this->store->remove($name, $message->id());
            }
        } catch (Throwable $e) {
            throw new MessageQueueException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function enqueue(string $name, Message $message): void
    {
        $this->queue->enqueue($name, $message);
    }

    /**
     * {@inheritdoc}
     */
    public function dequeue(string $name, int $timeout = 0): ?Message
    {
        return $this->queue->dequeue($name, $timeout);
    }

    /**
     * {@inheritdoc}
     */
    public function dequeueNonBlocking(string $name): ?Message
    {
        return $this->queue->dequeueNonBlocking($name);
    }

    /**
     * {@inheritdoc}
     */
    public function acknowledge(string $name, Message $message): void
    {
        $this->queue->acknowledge($name, $message);
    }

    /**
     * {@inheritdoc}
     */
    public function reject(string $name, Message $message, bool $requeue = false): void
    {
        try {
            $this->queue->reject($name, $message, $requeue);

            if ($requeue) {
                return;
            }

            $this->store->add($name, $message);
        } catch (Throwable $e) {
            throw new MessageQueueException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
