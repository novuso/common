<?php

namespace Novuso\Common\Application\EventStore;

use Novuso\Common\Application\EventStore\Exception\EventStoreException;
use Novuso\Common\Domain\Aggregate\EventRecord;
use Novuso\Common\Domain\Aggregate\EventStream;
use Novuso\Common\Domain\Identification\Identifier;
use Novuso\System\Type\Type;

/**
 * EventStore is the interface for a domain event store
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface EventStore
{
    /**
     * Appends an event record
     *
     * @param EventRecord $eventRecord The event record
     *
     * @return void
     *
     * @throws EventStoreException When an error occurs during processing
     */
    public function append(EventRecord $eventRecord): void;

    /**
     * Appends an event stream
     *
     * @param EventStream $eventStream The event stream
     *
     * @return void
     *
     * @throws EventStoreException When an error occurs during processing
     */
    public function appendStream(EventStream $eventStream): void;

    /**
     * Retrieves an event stream
     *
     * @param Type       $type  The aggregate type
     * @param Identifier $id    The aggregate ID
     * @param int|null   $first The first version or null for the beginning
     * @param int|null   $last  The last version or null for the remaining
     *
     * @return EventStream
     */
    public function readStream(Type $type, Identifier $id, ?int $first = null, ?int $last = null): EventStream;

    /**
     * Checks if an event stream exists
     *
     * @param Type       $type The aggregate type
     * @param Identifier $id   The aggregate ID
     *
     * @return bool
     */
    public function hasStream(Type $type, Identifier $id): bool;
}
