<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Process;

use Novuso\Common\Application\Process\Exception\ProcessException;

/**
 * Interface ProcessRunner
 */
interface ProcessRunner
{
    /**
     * Attaches a process
     */
    public function attach(Process $process): void;

    /**
     * Clears attached processes
     */
    public function clear(): void;

    /**
     * Runs attached processes
     *
     * Process error behavior defaults to throwing an exception when a child
     * process fails.
     *
     * To ignore process errors, pass ProcessError::IGNORE()
     *
     * To retry failed processes, pass ProcessError::RETRY()
     *
     * @throws ProcessException When an error occurs, depending on behavior
     */
    public function run(?ProcessErrorBehavior $errorBehavior = null): void;
}
