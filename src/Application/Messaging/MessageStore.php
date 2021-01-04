<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Messaging;

use Novuso\Common\Application\Messaging\Exception\MessageStoreException;
use Novuso\Common\Domain\Messaging\Message;
use Novuso\Common\Domain\Messaging\MessageId;

/**
 * Interface MessageStore
 */
interface MessageStore
{
    /**
     * Adds a message
     *
     * @throws MessageStoreException When an error occurs
     */
    public function add(string $name, Message $message): void;

    /**
     * Retrieves a message by ID
     *
     * @throws MessageStoreException When an error occurs
     */
    public function get(string $name, MessageId $messageId): ?Message;

    /**
     * Retrieves all messages
     *
     * @return iterable<Message>
     *
     * @throws MessageStoreException When an error occurs
     */
    public function getAll(string $name): iterable;

    /**
     * Removes a message by ID
     *
     * @throws MessageStoreException When an error occurs
     */
    public function remove(string $name, MessageId $messageId): void;
}
