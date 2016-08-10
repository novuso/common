<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Messaging\Command;

use Novuso\Common\Domain\Messaging\BaseMessage;
use Novuso\Common\Domain\Messaging\MessageId;
use Novuso\Common\Domain\Messaging\MessageType;
use Novuso\Common\Domain\Messaging\MetaData;
use Novuso\Common\Domain\Model\DateTime\DateTime;
use Novuso\System\Exception\DomainException;
use Novuso\System\Type\Type;
use Novuso\System\Utility\Test;
use Novuso\System\Utility\VarPrinter;

/**
 * CommandMessage is a domain command message
 *
 * @copyright Copyright (c) 2016, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class CommandMessage extends BaseMessage
{
    /**
     * Constructs CommandMessage
     *
     * @param MessageId $id        The message ID
     * @param DateTime  $timestamp The timestamp
     * @param Command   $payload   The payload
     * @param MetaData  $metaData  The meta data
     */
    public function __construct(MessageId $id, DateTime $timestamp, Command $payload, MetaData $metaData)
    {
        parent::__construct($id, MessageType::COMMAND(), $timestamp, $payload, $metaData);
    }

    /**
     * Creates instance for a command
     *
     * @param Command $command The command
     *
     * @return CommandMessage
     */
    public static function create(Command $command): CommandMessage
    {
        $timestamp = DateTime::now();
        $id = MessageId::generate();
        $metaData = MetaData::create();

        return new static($id, $timestamp, $command, $metaData);
    }

    /**
     * {@inheritdoc}
     */
    public static function deserialize(array $data): CommandMessage
    {
        $keys = ['id', 'type', 'timestamp', 'meta_data', 'payload_type', 'payload'];
        foreach ($keys as $key) {
            if (!isset($data[$key])) {
                $message = sprintf('Invalid serialization data: %s', VarPrinter::toString($data));
                throw new DomainException($message);
            }
        }

        if ($data['type'] !== MessageType::COMMAND) {
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
        /** @var string $payloadClass */
        $payloadClass = $payloadType->toClassName();

        assert(
            Test::implementsInterface($payloadClass, Command::class),
            sprintf('Unable to deserialize: %s', $payloadClass)
        );

        /** @var Command $payload */
        $payload = $payloadClass::fromArray($data['payload']);

        return new static($id, $timestamp, $payload, $metaData);
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
    public function withMetaData(MetaData $metaData)
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
     * {@inheritdoc}
     */
    public function mergeMetaData(MetaData $metaData)
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
