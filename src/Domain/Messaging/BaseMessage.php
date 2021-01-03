<?php

declare(strict_types=1);

namespace Novuso\Common\Domain\Messaging;

use Novuso\Common\Domain\Value\DateTime\DateTime;
use Novuso\System\Type\Type;
use Novuso\System\Utility\Assert;
use Novuso\System\Utility\ClassName;
use Novuso\System\Utility\Validate;

/**
 * Class BaseMessage
 */
abstract class BaseMessage implements Message
{
    protected Type $payloadType;

    /**
     * Constructs BaseMessage
     */
    protected function __construct(
        protected MessageId $id,
        protected MessageType $type,
        protected DateTime $timestamp,
        protected Payload $payload,
        protected MetaData $metaData
    ) {
        $this->payloadType = Type::create($this->payload);
    }

    /**
     * @inheritDoc
     */
    public function id(): MessageId
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function type(): MessageType
    {
        return $this->type;
    }

    /**
     * @inheritDoc
     */
    public function timestamp(): DateTime
    {
        return $this->timestamp;
    }

    /**
     * @inheritDoc
     */
    public function payload(): Payload
    {
        return $this->payload;
    }

    /**
     * @inheritDoc
     */
    public function payloadType(): Type
    {
        return $this->payloadType;
    }

    /**
     * @inheritDoc
     */
    public function metaData(): MetaData
    {
        return $this->metaData;
    }

    /**
     * @inheritDoc
     */
    public function toString(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_SLASHES);
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * @inheritDoc
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
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * @inheritDoc
     */
    public function arraySerialize(): array
    {
        return $this->toArray();
    }

    /**
     * @inheritDoc
     */
    public function compareTo(mixed $object): int
    {
        if ($this === $object) {
            return 0;
        }

        Assert::areSameType($this, $object);

        return $this->id->compareTo($object->id);
    }

    /**
     * @inheritDoc
     */
    public function equals(mixed $object): bool
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
     * @inheritDoc
     */
    public function hashValue(): string
    {
        return sprintf(
            '%s:%s',
            ClassName::short(static::class),
            $this->id->hashValue()
        );
    }
}
