<?php

namespace Novuso\Test\Common\Resources\Domain\Messaging\Command;

use Exception;
use Novuso\Common\Domain\Messaging\Command\CommandHandler;
use Novuso\Common\Domain\Messaging\Command\CommandMessage;

class ErrorUserHandler implements CommandHandler
{
    public function handle(CommandMessage $message): void
    {
        throw new Exception('Something went wrong');
    }
}
