<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Model;

use JsonSerializable;
use Novuso\Common\Domain\Identity\IdentifierInterface;
use Novuso\Common\Domain\Messaging\Event\EventMessage;
use Novuso\System\Exception\DomainException;
use Novuso\System\Serialization\SerializableInterface;
use Novuso\System\Type\Arrayable;
use Novuso\System\Type\Comparable;
use Novuso\System\Type\Equatable;
use Novuso\System\Type\Type;
use Novuso\System\Utility\Validate;
use Novuso\System\Utility\VarPrinter;

/**
 * EventRecord is an aggregate wrapper for a domain event message
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class EventRecord implements Arrayable, Comparable, Equatable, JsonSerializable, SerializableInterface
{
    /**
     * Event message
     *
     * @var EventMessage
     */
    protected $eventMessage;

    /**
     * Aggregate ID
     *
     * @var IdentifierInterface
     */
    protected $aggregateId;

    /**
     * Aggregate ID type
     *
     * @var Type
     */
    protected $aggregateIdType;

    /**
     * Aggregate type
     *
     * @var Type
     */
    protected $aggregateType;

    /**
     * Sequence number
     *
     * @var int
     */
    protected $sequenceNumber;

    /**
     * Constructs EventRecord
     *
     * @param EventMessage        $eventMessage   The event message
     * @param IdentifierInterface $aggregateId    The aggregate ID
     * @param Type                $aggregateType  The aggregate type
     * @param int                 $sequenceNumber The sequence number
     */
    public function __construct(
        EventMessage $eventMessage,
        IdentifierInterface $aggregateId,
        Type $aggregateType,
        int $sequenceNumber
    ) {
        $this->eventMessage = $eventMessage;
        $this->aggregateId = $aggregateId;
        $this->aggregateIdType = Type::create($aggregateId);
        $this->aggregateType = $aggregateType;
        $this->sequenceNumber = $sequenceNumber;
    }

    /**
     * {@inheritdoc}
     */
    public static function deserialize(array $data): EventRecord
    {
        $keys = [
            'event_message',
            'aggregate_id',
            'aggregate_id_type',
            'aggregate_type',
            'sequence_number'
        ];

        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                $message = sprintf(
                    'Invalid data format; missing %s key: %s',
                    $key,
                    VarPrinter::toString($data)
                );
                throw new DomainException($message);
            }
        }

        $eventMessage = EventMessage::deserialize($data['event_message']);
        /** @var IdentifierInterface|string $aggregateIdClass */
        $aggregateIdClass = Type::create($data['aggregate_id_type'])->toClassName();
        /** @var IdentifierInterface $aggregateId */
        $aggregateId = $aggregateIdClass::fromString($data['aggregate_id']);
        $aggregateType = Type::create($data['aggregate_type']);
        $sequenceNumber = (int) $data['sequence_number'];

        return new static($eventMessage, $aggregateId, $aggregateType, $sequenceNumber);
    }

    /**
     * Retrieves the event message
     *
     * @return EventMessage
     */
    public function eventMessage(): EventMessage
    {
        return $this->eventMessage;
    }

    /**
     * Retrieves the aggregate ID
     *
     * @return IdentifierInterface
     */
    public function aggregateId(): IdentifierInterface
    {
        return $this->aggregateId;
    }

    /**
     * Retrieves the aggregate type
     *
     * @return Type
     */
    public function aggregateType(): Type
    {
        return $this->aggregateType;
    }

    /**
     * Retrieves the sequence number
     *
     * @return int
     */
    public function sequenceNumber(): int
    {
        return $this->sequenceNumber;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        return [
            'event_message'     => $this->eventMessage->serialize(),
            'aggregate_id'      => $this->aggregateId->toString(),
            'aggregate_id_type' => $this->aggregateIdType->toString(),
            'aggregate_type'    => $this->aggregateType->toString(),
            'sequence_number'   => $this->sequenceNumber()
        ];
    }

    /**
     * Retrieves a representation for JSON encoding
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function serialize(): array
    {
        return $this->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function compareTo($object): int
    {
        if ($this === $object) {
            return 0;
        }

        assert(
            Validate::areSameType($this, $object),
            sprintf('Comparison requires instance of %s', static::class)
        );
        assert(
            Validate::areEqual($this->aggregateType, $object->aggregateType),
            'Comparison must be for a single aggregate type'
        );
        assert(
            Validate::areEqual($this->aggregateId, $object->aggregateId),
            'Comparison must be for a single aggregate identifier'
        );

        $thisSeq = $this->sequenceNumber;
        $thatSeq = $object->sequenceNumber;

        return $thisSeq <=> $thatSeq;
    }

    /**
     * {@inheritdoc}
     */
    public function equals($object): bool
    {
        if ($this === $object) {
            return true;
        }

        if (!Validate::areSameType($this, $object)) {
            return false;
        }

        return $this->hashValue() === $object->hashValue();
    }

    /**
     * {@inheritdoc}
     */
    public function hashValue(): string
    {
        return sprintf(
            '%s:%s:%d:%s',
            $this->aggregateType->toString(),
            $this->aggregateId->hashValue(),
            $this->sequenceNumber,
            $this->eventMessage->id()->hashValue()
        );
    }
}
