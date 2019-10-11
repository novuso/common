<?php declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Command\Routing;

use Novuso\Common\Domain\Messaging\Command\Command;
use Novuso\Common\Domain\Messaging\Command\CommandHandler;

/**
 * Class SimpleCommandRouter
 */
final class SimpleCommandRouter implements CommandRouter
{
    /**
     * Command map
     *
     * @var CommandMap
     */
    protected $commandMap;

    /**
     * Constructs SimpleCommandRouter
     *
     * @param CommandMap $commandMap The command map
     */
    public function __construct(CommandMap $commandMap)
    {
        $this->commandMap = $commandMap;
    }

    /**
     * {@inheritdoc}
     */
    public function match(Command $command): CommandHandler
    {
        return $this->commandMap->getHandler(get_class($command));
    }
}
