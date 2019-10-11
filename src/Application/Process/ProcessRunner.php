<?php declare(strict_types=1);

namespace Novuso\Common\Application\Process;

use Novuso\Common\Application\Process\Exception\ProcessException;

/**
 * Interface ProcessRunner
 */
interface ProcessRunner
{
    /**
     * Attaches a process
     *
     * @param Process $process The process
     *
     * @return void
     */
    public function attach(Process $process): void;

    /**
     * Clears attached processes
     *
     * @return void
     */
    public function clear(): void;

    /**
     * Runs attached processes
     *
     * Process error behavior defaults to throwing an exception when a child
     * process fails. To ignore process errors, pass ProcessError::IGNORE()
     *
     * @param ProcessErrorBehavior|null $errorBehavior The process error behavior
     *
     * @return void
     *
     * @throws ProcessException When an error occurs, depending on behavior
     */
    public function run(?ProcessErrorBehavior $errorBehavior = null): void;
}
