<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Messaging\Query;

use Novuso\Common\Domain\Messaging\BaseMessage;
use Novuso\Common\Domain\Messaging\MessageId;
use Novuso\Common\Domain\Messaging\MessageType;
use Novuso\Common\Domain\Messaging\MetaData;
use Novuso\Common\Domain\Model\DateTime\DateTime;
use Novuso\System\Exception\DomainException;
use Novuso\System\Utility\VarPrinter;

/**
 * QueryMessage is a domain query message
 *
 * @copyright Copyright (c) 2016, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class QueryMessage extends BaseMessage
{
    /**
     * Constructs QueryMessage
     *
     * @param MessageId $id        The message ID
     * @param DateTime  $timestamp The timestamp
     * @param Query     $payload   The payload
     * @param MetaData  $metaData  The meta data
     */
    public function __construct(MessageId $id, DateTime $timestamp, Query $payload, MetaData $metaData)
    {
        parent::__construct($id, MessageType::QUERY(), $timestamp, $payload, $metaData);
    }

    /**
     * Creates instance for a query
     *
     * @param Query $query The query
     *
     * @return QueryMessage
     */
    public static function create(Query $query): QueryMessage
    {
        $timestamp = DateTime::now();
        $id = MessageId::generate();
        $metaData = MetaData::create();

        return new static($id, $timestamp, $query, $metaData);
    }

    /**
     * {@inheritdoc}
     */
    public function withMetaData(MetaData $metaData)
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
     * {@inheritdoc}
     */
    public function mergeMetaData(MetaData $metaData)
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
