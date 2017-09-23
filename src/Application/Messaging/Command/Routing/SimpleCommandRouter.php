<?php declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Command\Routing;

use Novuso\Common\Domain\Messaging\Command\CommandHandler;
use Novuso\Common\Domain\Messaging\Command\Command;

/**
 * SimpleCommandRouter matches commands from a command map
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class SimpleCommandRouter implements CommandRouter
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
