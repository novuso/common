<?php

declare(strict_types=1);

namespace Novuso\Common\Domain\Messaging\Command;

use Throwable;

/**
 * Interface CommandHandler
 */
interface CommandHandler
{
    /**
     * Retrieves command registration
     *
     * Returns the fully qualified class name for the command that this service
     * is meant to handle.
     */
    public static function commandRegistration(): string;

    /**
     * Handles a command
     *
     * @throws Throwable When an error occurs
     */
    public function handle(CommandMessage $message): void;
}
