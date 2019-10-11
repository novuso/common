<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Messaging;

use Novuso\Common\Domain\Value\DateTime\DateTime;
use Novuso\System\Type\Type;
use Novuso\System\Utility\Assert;
use Novuso\System\Utility\Validate;

/**
 * Class BaseMessage
 */
abstract class BaseMessage implements Message
{
    /**
     * Message ID
     *
     * @var MessageId
     */
    protected $id;

    /**
     * Message type
     *
     * @var MessageType
     */
    protected $type;

    /**
     * Timestamp
     *
     * @var DateTime
     */
    protected $timestamp;

    /**
     * Payload
     *
     * @var Payload
     */
    protected $payload;

    /**
     * Payload type
     *
     * @var Type
     */
    protected $payloadType;

    /**
     * Meta data
     *
     * @var MetaData
     */
    protected $metaData;

    /**
     * Constructs BaseMessage
     *
     * @param MessageId   $id        The message ID
     * @param MessageType $type      The message type
     * @param DateTime    $timestamp The timestamp
     * @param Payload     $payload   The payload
     * @param MetaData    $metaData  The meta data
     */
    protected function __construct(
        MessageId $id,
        MessageType $type,
        DateTime $timestamp,
        Payload $payload,
        MetaData $metaData
    ) {
        $this->id = $id;
        $this->type = $type;
        $this->timestamp = $timestamp;
        $this->payload = $payload;
        $this->payloadType = Type::create($payload);
        $this->metaData = $metaData;
    }

    /**
     * {@inheritdoc}
     */
    public function id(): MessageId
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function type(): MessageType
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function timestamp(): DateTime
    {
        return $this->timestamp;
    }

    /**
     * {@inheritdoc}
     */
    public function payload(): Payload
    {
        return $this->payload;
    }

    /**
     * {@inheritdoc}
     */
    public function payloadType(): Type
    {
        return $this->payloadType;
    }

    /**
     * {@inheritdoc}
     */
    public function metaData(): MetaData
    {
        return $this->metaData;
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_SLASHES);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        return [
            'id'           => $this->id->toString(),
            'type'         => $this->type->value(),
            'timestamp'    => $this->timestamp->toString(),
            'payload_type' => $this->payloadType->toString(),
            'payload'      => $this->payload->toArray(),
            'meta_data'    => $this->metaData->toArray()
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
    public function arraySerialize(): array
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

        Assert::areSameType($this, $object);

        return $this->id->compareTo($object->id);
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

        return $this->id->equals($object->id);
    }

    /**
     * {@inheritdoc}
     */
    public function hashValue(): string
    {
        return $this->id->hashValue();
    }
}
