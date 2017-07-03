<?php

namespace Novuso\Test\Common\Resources\Domain\Messaging\Command;

use Exception;
use Novuso\Common\Domain\Messaging\Command\Command;
use Novuso\Common\Domain\Messaging\Command\CommandHandler;

class ErrorUserHandler implements CommandHandler
{
    public function handle(Command $command): void
    {
        throw new Exception('Something went wrong');
    }
}
