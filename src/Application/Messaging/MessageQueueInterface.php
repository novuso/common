<?php declare(strict_types=1);

namespace Novuso\Common\Application\Messaging;

use Novuso\Common\Domain\Messaging\MessageInterface;

/**
 * MessageQueueInterface is the interface for a message queue
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface MessageQueueInterface
{
    /**
     * Adds a message to the queue
     *
     * @param string           $topic   The topic name
     * @param MessageInterface $message The message
     *
     * @return void
     */
    public function enqueue(string $topic, MessageInterface $message): void;

    /**
     * Removes and returns the next message from the queue
     *
     * @param string $topic The topic name
     *
     * @return MessageInterface|null
     */
    public function dequeue(string $topic): ?MessageInterface;

    /**
     * Acknowledges the message was processed
     *
     * @param string           $topic   The topic name
     * @param MessageInterface $message The message
     *
     * @return void
     */
    public function ack(string $topic, MessageInterface $message): void;
}
