<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Command\Routing;

use Novuso\Common\Domain\Messaging\Command\Command;
use Novuso\Common\Domain\Messaging\Command\CommandHandler;

/**
 * Class SimpleCommandRouter
 */
final class SimpleCommandRouter implements CommandRouter
{
    /**
     * Constructs SimpleCommandRouter
     */
    public function __construct(protected CommandMap $commandMap)
    {
    }

    /**
     * @inheritDoc
     */
    public function match(Command $command): CommandHandler
    {
        return $this->commandMap->getHandler(get_class($command));
    }
}
