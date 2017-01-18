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
     * @param Command $command The command
     *
     * @return void
     *
     * @throws Exception When an error occurs
     */
    public function execute(Command $command);
}
