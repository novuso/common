<?php declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Command\Routing;

use Novuso\Common\Domain\Messaging\Command\CommandHandlerInterface;
use Novuso\Common\Domain\Messaging\Command\CommandInterface;
use Novuso\System\Exception\LookupException;

/**
 * CommandRouterInterface matches a command to a handler
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface CommandRouterInterface
{
    /**
     * Matches a command to a handler
     *
     * @param CommandInterface $command The command
     *
     * @return CommandHandlerInterface
     *
     * @throws LookupException When the handler is not found
     */
    public function match(CommandInterface $command): CommandHandlerInterface;
}
