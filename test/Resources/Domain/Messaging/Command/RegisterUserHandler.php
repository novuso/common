<?php

namespace Novuso\Test\Common\Resources\Domain\Messaging\Command;

use Novuso\Common\Domain\Messaging\Command\CommandHandlerInterface;
use Novuso\Common\Domain\Messaging\Command\CommandMessage;

class RegisterUserHandler implements CommandHandlerInterface
{
    protected $handled = false;

    public function handle(CommandMessage $message): void
    {
        $this->handled = true;
    }

    public function isHandled(): bool
    {
        return $this->handled;
    }
}
