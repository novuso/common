<?php declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Command;

use Novuso\Common\Application\Messaging\MessageQueue;
use Novuso\Common\Domain\Messaging\Command\CommandBus;
use Novuso\Common\Domain\Messaging\Command\Command;
use Novuso\Common\Domain\Messaging\Command\CommandMessage;

/**
 * QueueingCommandBus dispatches commands to a message queue
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class QueueingCommandBus implements CommandBus
{
    /**
     * Message queue
     *
     * @var MessageQueue
     */
    protected $messageQueue;

    /**
     * Topic name
     *
     * @var string
     */
    protected $topicName;

    /**
     * Constructs QueueingCommandBus
     *
     * @param MessageQueue $messageQueue The message queue
     * @param string       $topicName    The topic name
     */
    public function __construct(MessageQueue $messageQueue, string $topicName)
    {
        $this->messageQueue = $messageQueue;
        $this->topicName = $topicName;
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
        $this->messageQueue->enqueue($this->topicName, $message);
    }
}
