<?php declare(strict_types=1);

namespace Novuso\Common\Application\Messaging;

use Novuso\Common\Domain\Messaging\Message;

/**
 * MessageQueue is the interface for a message queue
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface MessageQueue
{
    /**
     * Adds a message to the queue
     *
     * @param string  $topic   The topic name
     * @param Message $message The message
     *
     * @return void
     */
    public function enqueue(string $topic, Message $message): void;

    /**
     * Removes and returns the next message from the queue
     *
     * @param string $topic The topic name
     *
     * @return Message|null
     */
    public function dequeue(string $topic): ?Message;

    /**
     * Acknowledges the message was processed
     *
     * @param string  $topic   The topic name
     * @param Message $message The message
     *
     * @return void
     */
    public function ack(string $topic, Message $message): void;
}
