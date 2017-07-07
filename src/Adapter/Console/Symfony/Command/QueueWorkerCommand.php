<?php declare(strict_types=1);

namespace Novuso\Common\Adapter\Console\Symfony\Command;

use Novuso\Common\Adapter\Console\Symfony\Command;
use Novuso\Common\Domain\Messaging\Command\CommandBus;
use Novuso\Common\Domain\Messaging\Command\CommandMessage;
use Novuso\Common\Domain\Messaging\Event\EventDispatcher;
use Novuso\Common\Domain\Messaging\Event\EventMessage;
use Novuso\Common\Domain\Messaging\MessageQueue;
use Novuso\Common\Domain\Messaging\MessageType;
use Symfony\Component\Console\Input\InputOption;

/**
 * QueueWorkerCommand is command that consumes messages from a queue
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class QueueWorkerCommand extends Command
{
    /**
     * Command name
     *
     * @var string
     */
    protected $name = 'queue:worker';

    /**
     * Command description
     *
     * @var string
     */
    protected $description = 'Handles the message queue';

    /**
     * Command bus
     *
     * @var CommandBus
     */
    protected $commandBus;

    /**
     * Event dispatcher
     *
     * @var EventDispatcher
     */
    protected $eventDispatcher;

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
     * Constructs QueueWorkerCommand
     *
     * @param CommandBus      $commandBus      The command bus
     * @param EventDispatcher $eventDispatcher The event dispatcher
     * @param MessageQueue    $messageQueue    The message queue
     * @param string          $channelName     The channel name
     */
    public function __construct(
        CommandBus $commandBus,
        EventDispatcher $eventDispatcher,
        MessageQueue $messageQueue,
        string $channelName
    ) {
        $this->commandBus = $commandBus;
        $this->eventDispatcher = $eventDispatcher;
        $this->messageQueue = $messageQueue;
        $this->channelName = $channelName;
        parent::__construct();
    }

    /**
     * Fires the command
     *
     * @return int
     */
    protected function fire(): int
    {
        while (true) {
            $message = $this->messageQueue->dequeue($this->channelName);

            if ($message === null) {
                // wait 100ms
                usleep(100000);
                continue;
            }

            switch ($message->type()->value()) {
                case MessageType::COMMAND:
                    /** @var CommandMessage $message */
                    $this->commandBus->dispatch($message);
                    break;
                case MessageType::EVENT:
                    /** @var EventMessage $message */
                    $this->eventDispatcher->dispatch($message);
                    break;
            }

            $this->messageQueue->ack($this->channelName, $message);

            if (!$this->option('persist')) {
                return 0;
            }
        }

        return 0;
    }

    /**
     * Retrieves the command options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            ['persist', 'p', InputOption::VALUE_NONE, 'Worker should not exit after each message']
        ];
    }
}
