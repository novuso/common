<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Messaging\Command;

use Throwable;

/**
 * Interface CommandBus
 */
interface CommandBus
{
    /**
     * Executes a command
     *
     * The bus should wrap the command in a command message, then dispatch
     *
     * @throws Throwable When an error occurs
     */
    public function execute(Command $command): void;

    /**
     * Dispatches a command message
     *
     * @throws Throwable When an error occurs
     */
    public function dispatch(CommandMessage $message): void;
}
