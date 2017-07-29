<?php declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Command\Routing;

use Novuso\Common\Domain\Messaging\Command\CommandHandlerInterface;
use Novuso\Common\Domain\Messaging\Command\CommandInterface;

/**
 * CommandRouter matches commands from a command map
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class CommandRouter implements CommandRouterInterface
{
    /**
     * Command map
     *
     * @var CommandMapInterface
     */
    protected $commandMap;

    /**
     * Constructs CommandRouter
     *
     * @param CommandMapInterface $commandMap The command map
     */
    public function __construct(CommandMapInterface $commandMap)
    {
        $this->commandMap = $commandMap;
    }

    /**
     * {@inheritdoc}
     */
    public function match(CommandInterface $command): CommandHandlerInterface
    {
        return $this->commandMap->getHandler(get_class($command));
    }
}
