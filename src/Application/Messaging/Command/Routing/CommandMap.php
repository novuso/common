<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Command\Routing;

use Novuso\Common\Domain\Messaging\Command\CommandHandler;
use Novuso\System\Exception\LookupException;

/**
 * Interface CommandMap
 */
interface CommandMap
{
    /**
     * Retrieves handler by command class name
     *
     * @throws LookupException When a handler is not registered
     */
    public function getHandler(string $commandClass): CommandHandler;

    /**
     * Checks if a handler is defined for a command
     */
    public function hasHandler(string $commandClass): bool;
}
