<?php declare(strict_types = 1);

namespace Novuso\Common\Application\Process;

use Exception;

/**
 * ProcessManager is the interface for a process manager
 *
 * @copyright Copyright (c) 2016, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface ProcessManager
{
    /**
     * Attaches a process
     *
     * @param Process $process The process
     *
     * @return void
     */
    public function attach(Process $process);

    /**
     * Clears attached processes
     *
     * @return void
     */
    public function clear();

    /**
     * Runs attached processes
     *
     * @return void
     *
     * @throws Exception When an error occurs
     */
    public function run();
}
