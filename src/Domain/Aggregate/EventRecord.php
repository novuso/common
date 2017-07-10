<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Aggregate;

use JsonSerializable;
use Novuso\Common\Domain\Identification\Identifier;
use Novuso\Common\Domain\Messaging\Event\EventMessage;
use Novuso\System\Exception\DomainException;
use Novuso\System\Serialization\Serializable;
use Novuso\System\Type\Arrayable;
use Novuso\System\Type\Comparable;
use Novuso\System\Type\Equatable;
use Novuso\System\Type\Type;
use Novuso\System\Utility\Validate;
use function Novuso\Common\Functions\{
    same_type,
    type,
    var_print
};

/**
 * EventRecord is an aggregate wrapper for a domain event message
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class EventRecord implements Arrayable, Comparable, Equatable, JsonSerializable, Serializable
{
    /**
     * Event message
     *
     * @var EventMessage
     */
    protected $eventMessage;

    /**
     * Event message type
     *
     * @var Type
     */
    protected $eventMessageType;

    /**
     * Aggregate ID
     *
     * @var Identifier
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
     * @param EventMessage $eventMessage   The event message
     * @param Identifier   $aggregateId    The aggregate ID
     * @param Type         $aggregateType  The aggregate type
     * @param int          $sequenceNumber The sequence number
     */
    public function __construct(
        EventMessage $eventMessage,
        Identifier $aggregateId,
        Type $aggregateType,
        int $sequenceNumber
    ) {
        $this->eventMessage = $eventMessage;
        $this->eventMessageType = type($eventMessage);
        $this->aggregateId = $aggregateId;
        $this->aggregateIdType = type($aggregateId);
        $this->aggregateType = $aggregateType;
        $this->sequenceNumber = $sequenceNumber;
    }

    /**
     * {@inheritdoc}
     */
    public static function deserialize(array $data): EventRecord
    {
        $keys = [
            'aggregate_id',
            'aggregate_id_type',
            'aggregate_type',
            'sequence_number',
            'event_message',
            'event_message_type'
        ];

        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                $message = sprintf(
                    'Invalid data format; missing %s key: %s',
                    $key,
                    var_print($data)
                );
                throw new DomainException($message);
            }
        }

        /** @var Identifier|string $aggregateIdClass */
        $aggregateIdClass = type($data['aggregate_id_type'])->toClassName();
        /** @var Identifier $aggregateId */
        $aggregateId = $aggregateIdClass::fromString($data['aggregate_id']);
        $aggregateType = type($data['aggregate_type']);
        $sequenceNumber = (int) $data['sequence_number'];
        /** @var EventMessage|string $eventMessageType */
        $eventMessageType = type($data['event_message_type'])->toClassName();
        $eventMessage = $eventMessageType::deserialize($data['event_message']);

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
     * @return Identifier
     */
    public function aggregateId(): Identifier
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
            'event_message'      => $this->eventMessage->serialize(),
            'event_message_type' => $this->eventMessageType->toString(),
            'aggregate_id'       => $this->aggregateId->toString(),
            'aggregate_id_type'  => $this->aggregateIdType->toString(),
            'aggregate_type'     => $this->aggregateType->toString(),
            'sequence_number'    => $this->sequenceNumber()
        ];
    }

    /**
     * {@inheritdoc}
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
            same_type($this, $object),
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

        if (!same_type($this, $object)) {
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
