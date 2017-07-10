<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Messaging;

use Novuso\Common\Domain\DateTime\DateTime;
use Novuso\System\Type\Type;
use function Novuso\Common\Functions\{
    collect,
    same_type,
    type,
    var_print
};

/**
 * BaseMessage is the base class for a domain message
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
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
        $this->payloadType = type($payload);
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
        return sprintf('{%s}', collect()
            ->push(sprintf('id:%s', $this->id->toString()))
            ->push(sprintf('type:%s', $this->type->value()))
            ->push(sprintf('timestamp:%s', $this->timestamp->toString()))
            ->push(sprintf('meta_data:{%s}', collect($this->metaData->toArray())
                ->implode(',', function ($value, $key) {
                    return sprintf('%s:%s', $key, var_print($value));
                })))
            ->push(sprintf('payload_type:%s', $this->payloadType->toString()))
            ->push(sprintf('payload:{%s}', collect($this->payload->toArray())
                ->implode(',', function ($value, $key) {
                    return sprintf('%s:%s', $key, var_print($value));
                })))
            ->implode(','));
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
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
            'meta_data'    => $this->metaData->toArray(),
            'payload_type' => $this->payloadType->toString(),
            'payload'      => $this->payload->toArray()
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

        $strComp = strnatcmp($this->toString(), $object->toString());

        return $strComp <=> 0;
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

        return $this->toString() === $object->toString();
    }

    /**
     * {@inheritdoc}
     */
    public function hashValue(): string
    {
        return $this->toString();
    }
}
