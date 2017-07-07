<?php declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Command;

use Novuso\Common\Domain\Messaging\Command\Command;
use Novuso\Common\Domain\Messaging\Command\CommandBus;
use Novuso\Common\Domain\Messaging\Command\CommandMessage;
use Novuso\Common\Domain\Messaging\MessageQueue;

/**
 * AsyncCommandBus dispatches commands to a message queue
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class AsyncCommandBus implements CommandBus
{
    /**
     * Message queue
     *
     * @var MessageQueue
     */
    protected $messageQueue;

    /**
     * Channel name
     *
     * @var string
     */
    protected $channelName;

    /**
     * Constructs AsyncCommandBus
     *
     * @param MessageQueue $messageQueue The message queue
     * @param string       $channelName  The channel name
     */
    public function __construct(MessageQueue $messageQueue, string $channelName)
    {
        $this->messageQueue = $messageQueue;
        $this->channelName = $channelName;
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
        $this->messageQueue->enqueue($this->channelName, $message);
    }
}
