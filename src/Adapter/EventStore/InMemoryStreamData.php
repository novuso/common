<?php declare(strict_types=1);

namespace Novuso\Common\Adapter\EventStore;

use Countable;
use Novuso\Common\Domain\Aggregate\EventRecord;
use Novuso\System\Utility\Validate;

/**
 * InMemoryStreamData is in-memory stream storage
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class InMemoryStreamData implements Countable
{
    /**
     * Version
     *
     * @var int|null
     */
    protected $version;

    /**
     * Event records
     *
     * @var EventRecord[]
     */
    protected $events;

    /**
     * Retrieves the count
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->events);
    }

    /**
     * Sets the version
     *
     * @param int $version The version
     *
     * @return void
     */
    public function setVersion(int $version): void
    {
        $this->version = $version;
    }

    /**
     * Retrieves the version
     *
     * @return int|null
     */
    public function getVersion(): ?int
    {
        return $this->version;
    }

    /**
     * Adds an event record
     *
     * @param EventRecord $event The event record
     *
     * @return void
     */
    public function addEvent(EventRecord $event): void
    {
        $sequence = $event->sequenceNumber();

        assert(
            !Validate::keyIsset($this->events, $sequence),
            sprintf('An event with sequence %d is already committed', $sequence)
        );

        $this->events[$sequence] = $event;
    }

    /**
     * Retrieves event records
     *
     * @return EventRecord[]
     */
    public function getEvents(): array
    {
        return array_values($this->events);
    }
}
