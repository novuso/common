<?php declare(strict_types=1);

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
     * @param string  $name    The queue name
     * @param Message $message The message
     *
     * @return void
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
     * @param string $name    The queue name
     * @param int    $timeout The timeout in seconds
     *
     * @return Message|null
     *
     * @throws MessageQueueException When an error occurs
     */
    public function dequeue(string $name, int $timeout = 0): ?Message;

    /**
     * Removes and returns the next message if available now
     *
     * @param string $name The queue name
     *
     * @return Message|null
     *
     * @throws MessageQueueException When an error occurs
     */
    public function dequeueNonBlocking(string $name): ?Message;

    /**
     * Acknowledges the message
     *
     * @param string  $name    The queue name
     * @param Message $message The message
     *
     * @return void
     *
     * @throws MessageQueueException When an error occurs
     */
    public function acknowledge(string $name, Message $message): void;

    /**
     * Rejects the message
     *
     * @param string  $name    The queue name
     * @param Message $message The message
     * @param bool    $requeue Whether to requeue the message
     *
     * @return void
     *
     * @throws MessageQueueException When an error occurs
     */
    public function reject(string $name, Message $message, bool $requeue = false): void;
}
