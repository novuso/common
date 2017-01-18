<?php declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Command\Routing;

use Novuso\Common\Domain\Messaging\Command\Command;
use Novuso\Common\Domain\Messaging\Command\CommandHandler;

/**
 * InMemoryCommandRouter matches commands from an in-memory map
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class InMemoryCommandRouter implements CommandRouter
{
    /**
     * Command map
     *
     * @var InMemoryCommandMap
     */
    protected $commandMap;

    /**
     * Constructs InMemoryCommandRouter
     *
     * @param InMemoryCommandMap $commandMap The command map
     */
    public function __construct(InMemoryCommandMap $commandMap)
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
