<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Messaging;

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
     * @param Message $message The message
     *
     * @return void
     */
    public function enqueue(Message $message): void;

    /**
     * Removes and returns the next message from the queue
     *
     * @return Message
     */
    public function dequeue(): Message;

    /**
     * Acknowledges the message was processed
     *
     * @param Message $message The message
     *
     * @return void
     */
    public function ack(Message $message): void;
}
