<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Messaging\Command;

use Exception;

/**
 * CommandBus is the interface for a command bus
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
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
     * @throws Exception When an error occurs
     */
    public function execute(Command $command): void;

    /**
     * Dispatches a command message
     *
     * @param CommandMessage $message The command message
     *
     * @return void
     *
     * @throws Exception When an error occurs
     */
    public function dispatch(CommandMessage $message): void;
}
