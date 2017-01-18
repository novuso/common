<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Messaging\Command;

use Exception;

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
     * Handles a command
     *
     * @param Command $command The command
     *
     * @return void
     *
     * @throws Exception When an error occurs
     */
    public function handle(Command $command);
}
