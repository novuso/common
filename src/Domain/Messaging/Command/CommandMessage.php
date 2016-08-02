<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Messaging\Command;

use Novuso\Common\Domain\Messaging\BaseMessage;
use Novuso\Common\Domain\Messaging\MessageId;
use Novuso\Common\Domain\Messaging\MessageType;
use Novuso\Common\Domain\Messaging\MetaData;
use Novuso\Common\Domain\Model\DateTime\DateTime;
use Novuso\System\Exception\DomainException;
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
