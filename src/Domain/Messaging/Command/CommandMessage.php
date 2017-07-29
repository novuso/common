<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Messaging\Command;

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
 * CommandMessage is a domain command message
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
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
     * @param CommandInterface   $payload   The payload
     * @param MetaData  $metaData  The meta data
     */
    public function __construct(MessageId $id, DateTime $timestamp, CommandInterface $payload, MetaData $metaData)
    {
        parent::__construct($id, MessageType::COMMAND(), $timestamp, $payload, $metaData);
    }

    /**
     * Creates instance for a command
     *
     * @param CommandInterface $command The command
     *
     * @return CommandMessage
     */
    public static function create(CommandInterface $command): CommandMessage
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
        /** @var CommandInterface|string $payloadClass */
        $payloadClass = $payloadType->toClassName();

        assert(
            Validate::implementsInterface($payloadClass, CommandInterface::class),
            sprintf('Unable to deserialize: %s', $payloadClass)
        );

        /** @var CommandInterface $payload */
        $payload = $payloadClass::fromArray($data['payload']);

        return new static($id, $timestamp, $payload, $metaData);
    }

    /**
     * {@inheritdoc}
     */
    public function withMetaData(MetaData $metaData): CommandMessage
    {
        /** @var CommandInterface $command */
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
    public function mergeMetaData(MetaData $metaData): CommandMessage
    {
        $meta = clone $this->metaData;
        $meta->merge($metaData);

        /** @var CommandInterface $command */
        $command = $this->payload;

        return new static(
            $this->id,
            $this->timestamp,
            $command,
            $meta
        );
    }
}
