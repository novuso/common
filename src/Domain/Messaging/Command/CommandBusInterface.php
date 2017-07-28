<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Messaging\Command;

use Throwable;

/**
 * CommandBusInterface is the interface for a command bus
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface CommandBusInterface
{
    /**
     * Executes a command
     *
     * The bus should wrap the command in a command message, then dispatch
     *
     * @param CommandInterface $command The command
     *
     * @return void
     *
     * @throws Throwable When an error occurs
     */
    public function execute(CommandInterface $command): void;

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
