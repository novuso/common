<?php

declare(strict_types=1);

namespace Novuso\Common\Domain\Messaging\Query;

use Novuso\Common\Domain\Messaging\BaseMessage;
use Novuso\Common\Domain\Messaging\MessageId;
use Novuso\Common\Domain\Messaging\MessageType;
use Novuso\Common\Domain\Messaging\MetaData;
use Novuso\Common\Domain\Value\DateTime\DateTime;
use Novuso\System\Exception\DomainException;
use Novuso\System\Type\Type;
use Novuso\System\Utility\Assert;
use Novuso\System\Utility\VarPrinter;

/**
 * Class QueryMessage
 */
class QueryMessage extends BaseMessage
{
    /**
     * Constructs QueryMessage
     *
     * @internal
     */
    protected function __construct(
        MessageId $id,
        DateTime $timestamp,
        Query $payload,
        MetaData $metaData
    ) {
        parent::__construct(
            $id,
            MessageType::QUERY(),
            $timestamp,
            $payload,
            $metaData
        );
    }

    /**
     * Creates instance for a query
     */
    public static function create(Query $query): static
    {
        $timestamp = DateTime::now();
        $id = MessageId::generate();
        $metaData = MetaData::create();

        return new static($id, $timestamp, $query, $metaData);
    }

    /**
     * @inheritDoc
     */
    public static function arrayDeserialize(array $data): static
    {
        $keys = [
            'id',
            'type',
            'timestamp',
            'meta_data',
            'payload_type',
            'payload'
        ];

        foreach ($keys as $key) {
            if (!isset($data[$key])) {
                $message = sprintf(
                    'Invalid serialization data: %s',
                    VarPrinter::toString($data)
                );
                throw new DomainException($message);
            }
        }

        if ($data['type'] !== MessageType::QUERY) {
            $message = sprintf('Invalid message type: %s', $data['type']);
            throw new DomainException($message);
        }

        $id = MessageId::fromString($data['id']);
        $timestamp = DateTime::fromString($data['timestamp']);
        $metaData = MetaData::create($data['meta_data']);
        $payloadType = Type::create($data['payload_type']);
        /** @var Query|string $payloadClass */
        $payloadClass = $payloadType->toClassName();

        Assert::implementsInterface($payloadClass, Query::class);

        $payload = $payloadClass::fromArray($data['payload']);

        return new static($id, $timestamp, $payload, $metaData);
    }

    /**
     * @inheritDoc
     */
    public function withMetaData(MetaData $metaData): static
    {
        /** @var Query $query */
        $query = $this->payload;

        return new static(
            $this->id,
            $this->timestamp,
            $query,
            $metaData
        );
    }

    /**
     * @inheritDoc
     */
    public function mergeMetaData(MetaData $metaData): static
    {
        $meta = clone $this->metaData;
        $meta->merge($metaData);

        /** @var Query $query */
        $query = $this->payload;

        return new static(
            $this->id,
            $this->timestamp,
            $query,
            $meta
        );
    }
}
