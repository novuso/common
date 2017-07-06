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
     * @param string  $channel The channel name
     * @param Message $message The message
     *
     * @return void
     */
    public function enqueue(string $channel, Message $message): void;

    /**
     * Removes and returns the next message from the queue
     *
     * @param string $channel The channel name
     *
     * @return Message|null
     */
    public function dequeue(string $channel): ?Message;

    /**
     * Acknowledges the message was processed
     *
     * @param string  $channel The channel name
     * @param Message $message The message
     *
     * @return void
     */
    public function ack(string $channel, Message $message): void;
}
