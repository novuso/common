<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Messaging\Event;

use Novuso\Common\Domain\Messaging\BaseMessage;
use Novuso\Common\Domain\Messaging\MessageId;
use Novuso\Common\Domain\Messaging\MessageType;
use Novuso\Common\Domain\Messaging\MetaData;
use Novuso\Common\Domain\Model\DateTime\DateTime;
use Novuso\System\Exception\DomainException;
use Novuso\System\Utility\VarPrinter;

/**
 * EventMessage is a domain event message
 *
 * @copyright Copyright (c) 2016, Novuso. <http://novuso.com>
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
        $timestamp = DateTime::now();
        $id = MessageId::generate();
        $metaData = MetaData::create();

        return new static($id, $timestamp, $event, $metaData);
    }

    /**
     * {@inheritdoc}
     */
    public function withMetaData(MetaData $metaData)
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
    public function mergeMetaData(MetaData $metaData)
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
