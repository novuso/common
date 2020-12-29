<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Messaging\Command;

use Throwable;

/**
 * Interface CommandFilter
 */
interface CommandFilter
{
    /**
     * Processes a command message and calls the next filter
     *
     * Signature of $next:
     *
     * <code>
     * function (CommandMessage $message): void {}
     * </code>
     *
     * @throws Throwable When an error occurs
     */
    public function process(CommandMessage $message, callable $next): void;
}
