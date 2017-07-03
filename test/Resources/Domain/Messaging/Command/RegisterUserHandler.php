<?php

namespace Novuso\Test\Common\Resources\Domain\Messaging\Command;

use Novuso\Common\Domain\Messaging\Command\Command;
use Novuso\Common\Domain\Messaging\Command\CommandHandler;

class RegisterUserHandler implements CommandHandler
{
    protected $handled = false;

    public function handle(Command $command): void
    {
        $this->handled = true;
    }

    public function isHandled(): bool
    {
        return $this->handled;
    }
}
