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
     * Constructs RecyclingMessageQueue
     */
    public function __construct(protected MessageQueue $queue, protected MessageStore $store) {}

    /**
     * Recycles a stored message
     *
     * @throws MessageQueueException
     */
    public function recycle(string $name, MessageId $messageId): void
    {
        try {
            $message = $this->store->get($name, $messageId);

            if ($message === null) {
                throw new LookupException(sprintf(
                    'Message %s not found',
                    $messageId->toString()
                ));
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
     * @inheritDoc
     */
    public function enqueue(string $name, Message $message): void
    {
        $this->queue->enqueue($name, $message);
    }

    /**
     * @inheritDoc
     */
    public function dequeue(string $name, int $timeout = 0): ?Message
    {
        return $this->queue->dequeue($name, $timeout);
    }

    /**
     * @inheritDoc
     */
    public function dequeueNonBlocking(string $name): ?Message
    {
        return $this->queue->dequeueNonBlocking($name);
    }

    /**
     * @inheritDoc
     */
    public function acknowledge(string $name, Message $message): void
    {
        $this->queue->acknowledge($name, $message);
    }

    /**
     * @inheritDoc
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
