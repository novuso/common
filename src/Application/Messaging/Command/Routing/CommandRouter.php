<?php declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Command\Routing;

use Novuso\Common\Domain\Messaging\Command\CommandHandler;
use Novuso\Common\Domain\Messaging\Command\Command;
use Novuso\System\Exception\LookupException;

/**
 * CommandRouter matches a command to a handler
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface CommandRouter
{
    /**
     * Matches a command to a handler
     *
     * @param Command $command The command
     *
     * @return CommandHandler
     *
     * @throws LookupException When the handler is not found
     */
    public function match(Command $command): CommandHandler;
}
