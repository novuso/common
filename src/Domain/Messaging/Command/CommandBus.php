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
     * @param Command $command The command
     *
     * @return void
     *
     * @throws Throwable When an error occurs
     */
    public function execute(Command $command): void;

    /**
     * Dispatches a command message
     *
     * @param CommandMessage $message The command message
     *
     * @return void
     *
     * @throws Throwable When an error occurs
     */
    public function dispatch(CommandMessage $message): void;
}
