<?php

declare(strict_types=1);

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
     * Constructs RoutingCommandBus
     */
    public function __construct(protected CommandRouter $commandRouter)
    {
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
        /** @var Command $command */
        $command = $message->payload();

        $this->commandRouter->match($command)->handle($message);
    }
}
