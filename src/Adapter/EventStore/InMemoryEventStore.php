<?php declare(strict_types=1);

namespace Novuso\Common\Adapter\EventStore;

use Novuso\Common\Application\EventStore\EventStore;
use Novuso\Common\Application\EventStore\Exception\ConcurrencyException;
use Novuso\Common\Application\EventStore\Exception\StreamNotFoundException;
use Novuso\Common\Domain\Aggregate\EventRecord;
use Novuso\Common\Domain\Aggregate\EventStream;
use Novuso\Common\Domain\Identification\Identifier;
use Novuso\System\Type\Type;

/**
 * InMemoryEventStore is an in-memory implementation of an event store
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class InMemoryEventStore implements EventStore
{
    /**
     * Stream data
     *
     * @var array
     */
    protected $streamData = [];

    /**
     * {@inheritdoc}
     */
    public function append(EventRecord $eventRecord): void
    {
        $idString = $eventRecord->aggregateId()->toString();
        $typeString = $eventRecord->aggregateType()->toString();

        if (!isset($this->streamData[$typeString])) {
            $this->streamData[$typeString] = [];
        }

        if (!isset($this->streamData[$typeString][$idString])) {
            $this->streamData[$typeString][$idString] = new InMemoryStreamData();
        }

        /** @var InMemoryStreamData $streamData */
        $streamData = $this->streamData[$typeString][$idString];

        $version = $eventRecord->sequenceNumber();
        if ($version === 0) {
            $expected = null;
        } else {
            $expected = $version - 1;
        }

        if ($streamData->getVersion() !== $expected) {
            $found = $streamData->getVersion();
            $message = sprintf(
                'Expected v%s; found v%s in stream [%s]{%s}',
                $expected,
                $found,
                $typeString,
                $idString
            );
            throw new ConcurrencyException($message);
        }

        $streamData->addEvent($eventRecord);
        $streamData->setVersion($version);
    }

    /**
     * {@inheritdoc}
     */
    public function appendStream(EventStream $eventStream): void
    {
        foreach ($eventStream as $eventRecord) {
            $this->append($eventRecord);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function readStream(Type $type, Identifier $id, ?int $first = null, ?int $last = null): EventStream
    {
        $idString = $id->toString();
        $typeString = $type->toString();

        if (!$this->hasStream($type, $id)) {
            $message = sprintf('Stream not found for [%s]{%s}', $typeString, $idString);
            throw new StreamNotFoundException($message);
        }

        /** @var InMemoryStreamData $streamData */
        $streamData = $this->streamData[$typeString][$idString];

        $count = count($streamData);
        $first = $this->normalizeFirst($first);
        $last = $this->normalizeLast($last, $count);
        $version = $streamData->getVersion();

        $events = array_filter(
            $streamData->getEvents(),
            function (EventRecord $event) use ($first, $last) {
                $sequence = $event->sequenceNumber();

                if ($sequence >= $first && $sequence <= $last) {
                    return true;
                }

                return false;
            }
        );

        return new EventStream($id, $type, $version, $version, $events);
    }

    /**
     * {@inheritdoc}
     */
    public function hasStream(Type $type, Identifier $id): bool
    {
        $idString = $id->toString();
        $typeString = $type->toString();

        if (!isset($this->streamData[$typeString])) {
            return false;
        }

        if (!isset($this->streamData[$typeString][$idString])) {
            return false;
        }

        return true;
    }

    /**
     * Retrieves the normalized first version
     *
     * @param int|null $first The first version or null for beginning
     *
     * @return int
     */
    protected function normalizeFirst(?int $first): int
    {
        if ($first === null) {
            return 0;
        }

        return $first;
    }

    /**
     * Retrieves the normalized last version
     *
     * @param int|null $last  The last version or null for remaining
     * @param int      $count The total event count
     *
     * @return int
     */
    protected function normalizeLast(?int $last, int $count): int
    {
        if ($last === null) {
            return $count - 1;
        }

        return $last;
    }
}
