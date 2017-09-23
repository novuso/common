<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Messaging\Command;

use Throwable;

/**
 * CommandHandler is the interface for a command handler
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface CommandHandler
{
    /**
     * Retrieves command registration
     *
     * Returns the fully qualified class name for the command that this service
     * is meant to handle.
     *
     * @return string
     */
    public static function commandRegistration(): string;

    /**
     * Handles a command
     *
     * @param CommandMessage $message The command message
     *
     * @return void
     *
     * @throws Throwable When an error occurs
     */
    public function handle(CommandMessage $message): void;
}
