<?php declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Command;

use Novuso\Common\Application\Messaging\Command\Routing\CommandRouterInterface;
use Novuso\Common\Domain\Messaging\Command\CommandBusInterface;
use Novuso\Common\Domain\Messaging\Command\CommandInterface;
use Novuso\Common\Domain\Messaging\Command\CommandMessage;

/**
 * SynchronousCommandBus routes a command to a single handler
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class SynchronousCommandBus implements CommandBusInterface
{
    /**
     * Command router
     *
     * @var CommandRouterInterface
     */
    protected $commandRouter;

    /**
     * Constructs SynchronousCommandBus
     *
     * @param CommandRouterInterface $commandRouter The command router
     */
    public function __construct(CommandRouterInterface $commandRouter)
    {
        $this->commandRouter = $commandRouter;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(CommandInterface $command): void
    {
        $this->dispatch(CommandMessage::create($command));
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(CommandMessage $message): void
    {
        /** @var CommandInterface $command */
        $command = $message->payload();

        $this->commandRouter->match($command)->handle($message);
    }
}
