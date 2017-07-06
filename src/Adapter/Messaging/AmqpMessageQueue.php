<?php declare(strict_types=1);

namespace Novuso\Common\Adapter\Messaging;

use Novuso\Common\Domain\Messaging\Message;
use Novuso\Common\Domain\Messaging\MessageQueue;
use Novuso\System\Serialization\Serializer;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AbstractConnection;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * AmqpMessageQueue is an AMQP message queue
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class AmqpMessageQueue implements MessageQueue
{
    /**
     * Exchange name
     *
     * @var string
     */
    protected const EXCHANGE = 'message-queue';

    /**
     * AMQP connection
     *
     * @var AbstractConnection
     */
    protected $connection;

    /**
     * AMQP channel
     *
     * @var AMQPChannel
     */
    protected $channel;

    /**
     * Serializer
     *
     * @var Serializer
     */
    protected $serializer;

    /**
     * Message parameters
     *
     * @var array
     */
    protected $messageParams;

    /**
     * Constructs AmqpMessageQueue
     *
     * @param AbstractConnection $connection The AMQP connection
     * @param Serializer         $serializer The serializer service
     */
    public function __construct(AbstractConnection $connection, Serializer $serializer)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
        $this->messageParams = [
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function enqueue(string $channel, Message $message): void
    {
        $this->createChannel($channel);
        $amqpMessage = new AMQPMessage(
            $this->serializer->serialize($message),
            $this->messageParams
        );
        $this->getChannel()->basic_publish($amqpMessage, static::EXCHANGE, $channel);
    }

    /**
     * {@inheritdoc}
     */
    public function dequeue(string $channel): ?Message
    {
        $this->createChannel($channel);
        $msg = $this->getChannel()->basic_get($channel);

        $message = null;

        if ($msg) {
            /** @var Message $message */
            $message = $this->serializer->deserialize($msg->body);
            $message->metaData()->set('delivery_tag', $msg->get('delivery_tag'));
        }

        return $message;
    }

    /**
     * {@inheritdoc}
     */
    public function ack(string $channel, Message $message): void
    {
        $this->getChannel()->basic_ack($message->metaData()->get('delivery_tag'));
    }

    /**
     * Creates channel if needed
     *
     * @param string $channel The channel name
     *
     * @return void
     */
    private function createChannel(string $channel): void
    {
        $ch = $this->getChannel();
        $ch->exchange_declare(static::EXCHANGE, 'direct', false, true, false);
        $ch->queue_declare($channel, false, true, false, false);
        $ch->queue_bind($channel, static::EXCHANGE, $channel);
    }

    /**
     * Retrieves the AMQP channel
     *
     * @return AMQPChannel
     */
    private function getChannel(): AMQPChannel
    {
        if ($this->channel === null) {
            $this->channel = $this->connection->channel();
        }

        return $this->channel;
    }
}
