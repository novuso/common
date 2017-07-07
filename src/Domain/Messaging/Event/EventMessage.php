<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Messaging\Event;

use Novuso\Common\Domain\DateTime\DateTime;
use Novuso\Common\Domain\Messaging\BaseMessage;
use Novuso\Common\Domain\Messaging\MessageId;
use Novuso\Common\Domain\Messaging\MessageType;
use Novuso\Common\Domain\Messaging\MetaData;
use Novuso\System\Exception\DomainException;
use Novuso\System\Type\Type;
use Novuso\System\Utility\Validate;
use function Novuso\Common\Functions\{
    date_time_now,
    date_time_from_string,
    type,
    var_print
};

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
     * @param MessageId $id        The message ID
     * @param DateTime  $timestamp The timestamp
     * @param Event     $payload   The payload
     * @param MetaData  $metaData  The meta data
     */
    public function __construct(MessageId $id, DateTime $timestamp, Event $payload, MetaData $metaData)
    {
        parent::__construct($id, MessageType::EVENT(), $timestamp, $payload, $metaData);
    }

    /**
     * Creates instance for an event
     *
     * @param Event $event The event
     *
     * @return EventMessage
     */
    public static function create(Event $event): EventMessage
    {
        $timestamp = date_time_now();
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
                $message = sprintf('Invalid serialization data: %s', var_print($data));
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
        $timestamp = date_time_from_string($data['timestamp']);
        /** @var MetaData $metaData */
        $metaData = MetaData::create($data['meta_data']);
        /** @var Type $payloadType */
        $payloadType = type($data['payload_type']);
        /** @var Event|string $payloadClass */
        $payloadClass = $payloadType->toClassName();

        assert(
            Validate::implementsInterface($payloadClass, Event::class),
            sprintf('Unable to deserialize: %s', $payloadClass)
        );

        /** @var Event $payload */
        $payload = $payloadClass::fromArray($data['payload']);

        return new static($id, $timestamp, $payload, $metaData);
    }

    /**
     * {@inheritdoc}
     */
    public function withMetaData(MetaData $metaData): EventMessage
    {
        /** @var Event $event */
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

        /** @var Event $event */
        $event = $this->payload;

        return new static(
            $this->id,
            $this->timestamp,
            $event,
            $meta
        );
    }
}
