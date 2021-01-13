<?php

declare(strict_types=1);

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
     * Constructs QueueingCommandBus
     */
    public function __construct(
        protected MessageQueue $messageQueue,
        protected string $queueName
    ) {
    }

    /**
     * @inheritDoc
     */
    public function execute(Command $command): void
    {
        $this->dispatch(CommandMessage::create($command));
    }

    /**
     * @inheritDoc
     */
    public function dispatch(CommandMessage $message): void
    {
        $this->messageQueue->enqueue($this->queueName, $message);
    }
}
