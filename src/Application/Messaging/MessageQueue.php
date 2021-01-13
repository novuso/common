<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Messaging;

use Novuso\Common\Application\Messaging\Exception\MessageQueueException;
use Novuso\Common\Domain\Messaging\Message;

/**
 * Interface MessageQueue
 */
interface MessageQueue
{
    /**
     * Adds a message to a queue
     *
     * @throws MessageQueueException When an error occurs
     */
    public function enqueue(string $name, Message $message): void;

    /**
     * Removes and returns the next message from a queue within the timeout
     *
     * This call blocks until a message arrives, the timeout expires, or this
     * message consumer is closed.
     *
     * A timeout of zero never expires, and the call blocks indefinitely.
     *
     * @throws MessageQueueException When an error occurs
     */
    public function dequeue(string $name, int $timeout = 0): ?Message;

    /**
     * Removes and returns the next message if available now
     *
     * @throws MessageQueueException When an error occurs
     */
    public function dequeueNonBlocking(string $name): ?Message;

    /**
     * Acknowledges the message
     *
     * @throws MessageQueueException When an error occurs
     */
    public function acknowledge(string $name, Message $message): void;

    /**
     * Rejects the message
     *
     * @throws MessageQueueException When an error occurs
     */
    public function reject(
        string $name,
        Message $message,
        bool $requeue = false
    ): void;
}
