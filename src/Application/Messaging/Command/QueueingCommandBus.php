<?php declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Command;

use Novuso\Common\Application\Messaging\MessageQueue;
use Novuso\Common\Domain\Messaging\Command\AsynchronousCommandBus;
use Novuso\Common\Domain\Messaging\Command\Command;
use Novuso\Common\Domain\Messaging\Command\CommandMessage;

/**
 * Class QueueingCommandBus
 */
final class QueueingCommandBus implements AsynchronousCommandBus
{
    /**
     * Message queue
     *
     * @var MessageQueue
     */
    protected $messageQueue;

    /**
     * Queue name
     *
     * @var string
     */
    protected $queueName;

    /**
     * Constructs QueueingCommandBus
     *
     * @param MessageQueue $messageQueue The message queue
     * @param string       $queueName    The queue name
     */
    public function __construct(MessageQueue $messageQueue, string $queueName)
    {
        $this->messageQueue = $messageQueue;
        $this->queueName = $queueName;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(Command $command): void
    {
        $this->dispatch(CommandMessage::create($command));
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(CommandMessage $message): void
    {
        $this->messageQueue->enqueue($this->queueName, $message);
    }
}
