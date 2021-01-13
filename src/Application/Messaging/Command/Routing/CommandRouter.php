<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Command\Routing;

use Novuso\Common\Domain\Messaging\Command\Command;
use Novuso\Common\Domain\Messaging\Command\CommandHandler;
use Novuso\System\Exception\LookupException;

/**
 * Interface CommandRouter
 */
interface CommandRouter
{
    /**
     * Matches a command to a handler
     *
     * @throws LookupException When the handler is not found
     */
    public function match(Command $command): CommandHandler;
}
