<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Messaging\Event;

use Novuso\Common\Domain\Messaging\BaseMessage;
use Novuso\Common\Domain\Messaging\MessageId;
use Novuso\Common\Domain\Messaging\MessageType;
use Novuso\Common\Domain\Messaging\MetaData;
use Novuso\Common\Domain\Value\DateTime\DateTime;
use Novuso\System\Exception\DomainException;
use Novuso\System\Type\Type;
use Novuso\System\Utility\Validate;
use Novuso\System\Utility\VarPrinter;

/**
 * EventMessage is a domain event message
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class EventMessage extends BaseMessage
{
    /**
     * Constructs EventMessage
     *
     * @param MessageId      $id        The message ID
     * @param DateTime       $timestamp The timestamp
     * @param EventInterface $payload   The payload
     * @param MetaData       $metaData  The meta data
     */
    public function __construct(MessageId $id, DateTime $timestamp, EventInterface $payload, MetaData $metaData)
    {
        parent::__construct($id, MessageType::EVENT(), $timestamp, $payload, $metaData);
    }

    /**
     * Creates instance for an event
     *
     * @param EventInterface $event The event
     *
     * @return EventMessage
     */
    public static function create(EventInterface $event): EventMessage
    {
        $timestamp = DateTime::now();
        $id = MessageId::generate();
        $metaData = MetaData::create();

        return new static($id, $timestamp, $event, $metaData);
    }

    /**
     * {@inheritdoc}
     */
    public static function deserialize(array $data): EventMessage
    {
        $keys = ['id', 'type', 'timestamp', 'meta_data', 'payload_type', 'payload'];
        foreach ($keys as $key) {
            if (!isset($data[$key])) {
                $message = sprintf('Invalid serialization data: %s', VarPrinter::toString($data));
                throw new DomainException($message);
            }
        }

        if ($data['type'] !== MessageType::EVENT) {
            $message = sprintf('Invalid message type: %s', $data['type']);
            throw new DomainException($message);
        }

        /** @var MessageId $id */
        $id = MessageId::fromString($data['id']);
        /** @var DateTime $timestamp */
        $timestamp = DateTime::fromString($data['timestamp']);
        /** @var MetaData $metaData */
        $metaData = MetaData::create($data['meta_data']);
        /** @var Type $payloadType */
        $payloadType = Type::create($data['payload_type']);
        /** @var EventInterface|string $payloadClass */
        $payloadClass = $payloadType->toClassName();

        assert(
            Validate::implementsInterface($payloadClass, EventInterface::class),
            sprintf('Unable to deserialize: %s', $payloadClass)
        );

        /** @var EventInterface $payload */
        $payload = $payloadClass::fromArray($data['payload']);

        return new static($id, $timestamp, $payload, $metaData);
    }

    /**
     * {@inheritdoc}
     */
    public function withMetaData(MetaData $metaData): EventMessage
    {
        /** @var EventInterface $event */
        $event = $this->payload;

        return new static(
            $this->id,
            $this->timestamp,
            $event,
            $metaData
        );
    }

    /**
     * {@inheritdoc}
     */
    public function mergeMetaData(MetaData $metaData): EventMessage
    {
        $meta = clone $this->metaData;
        $meta->merge($metaData);

        /** @var EventInterface $event */
        $event = $this->payload;

        return new static(
            $this->id,
            $this->timestamp,
            $event,
            $meta
        );
    }
}
