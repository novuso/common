<?php

namespace Novuso\Test\Common\Resources\Domain\Messaging\Command;

use Exception;
use Novuso\Common\Domain\Messaging\Command\CommandHandlerInterface;
use Novuso\Common\Domain\Messaging\Command\CommandMessage;

class ErrorUserHandler implements CommandHandlerInterface
{
    public static function commandRegistration(): string
    {
        return RegisterUserCommand::class;
    }

    public function handle(CommandMessage $message): void
    {
        throw new Exception('Something went wrong');
    }
}
