<?php

namespace Novuso\Test\Common\Resources\Domain\Messaging\Command;

use Novuso\Common\Domain\Messaging\Command\CommandHandler;
use Novuso\Common\Domain\Messaging\Command\CommandMessage;

class RegisterUserHandler implements CommandHandler
{
    protected $handled = false;

    public static function commandRegistration(): string
    {
        return RegisterUserCommand::class;
    }

    public function handle(CommandMessage $message): void
    {
        $this->handled = true;
    }

    public function isHandled(): bool
    {
        return $this->handled;
    }
}
