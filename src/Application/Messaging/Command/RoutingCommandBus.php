<?php declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Command;

use Novuso\Common\Application\Messaging\Command\Routing\CommandRouter;
use Novuso\Common\Domain\Messaging\Command\Command;
use Novuso\Common\Domain\Messaging\Command\CommandMessage;
use Novuso\Common\Domain\Messaging\Command\SynchronousCommandBus;

/**
 * Class RoutingCommandBus
 */
final class RoutingCommandBus implements SynchronousCommandBus
{
    /**
     * Command router
     *
     * @var CommandRouter
     */
    protected $commandRouter;

    /**
     * Constructs RoutingCommandBus
     *
     * @param CommandRouter $commandRouter The command router
     */
    public function __construct(CommandRouter $commandRouter)
    {
        $this->commandRouter = $commandRouter;
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
        /** @var Command $command */
        $command = $message->payload();

        $this->commandRouter->match($command)->handle($message);
    }
}
