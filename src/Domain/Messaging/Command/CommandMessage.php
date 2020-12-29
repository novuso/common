<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Messaging\Command;

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
 * Class CommandMessage
 */
final class CommandMessage extends BaseMessage
{
    /**
     * Constructs CommandMessage
     *
     * @internal
     */
    protected function __construct(MessageId $id, DateTime $timestamp, Command $payload, MetaData $metaData)
    {
        parent::__construct($id, MessageType::COMMAND(), $timestamp, $payload, $metaData);
    }

    /**
     * Creates instance for a command
     */
    public static function create(Command $command): static
    {
        $timestamp = DateTime::now();
        $id = MessageId::generate();
        $metaData = MetaData::create();

        return new static($id, $timestamp, $command, $metaData);
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

        if ($data['type'] !== MessageType::COMMAND) {
            $message = sprintf('Invalid message type: %s', $data['type']);
            throw new DomainException($message);
        }

        $id = MessageId::fromString($data['id']);
        $timestamp = DateTime::fromString($data['timestamp']);
        $metaData = MetaData::create($data['meta_data']);
        $payloadType = Type::create($data['payload_type']);
        /** @var Command|string $payloadClass */
        $payloadClass = $payloadType->toClassName();

        Assert::implementsInterface($payloadClass, Command::class);

        $payload = $payloadClass::fromArray($data['payload']);

        return new static($id, $timestamp, $payload, $metaData);
    }

    /**
     * @inheritDoc
     */
    public function withMetaData(MetaData $metaData): static
    {
        /** @var Command $command */
        $command = $this->payload;

        return new static(
            $this->id,
            $this->timestamp,
            $command,
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

        /** @var Command $command */
        $command = $this->payload;

        return new static(
            $this->id,
            $this->timestamp,
            $command,
            $meta
        );
    }
}
