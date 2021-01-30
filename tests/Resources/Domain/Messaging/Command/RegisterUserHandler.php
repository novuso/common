<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Resources\Domain\Messaging\Command;

use Novuso\Common\Domain\Messaging\Command\CommandHandler;
use Novuso\Common\Domain\Messaging\Command\CommandMessage;

/**
 * Class RegisterUserHandler
 */
class RegisterUserHandler implements CommandHandler
{
    protected bool $handled = false;

    public static function commandRegistration(): string
    {
        return RegisterUserCommand::class;
    }

    public function handle(CommandMessage $commandMessage): void
    {
        $this->handled = true;
    }

    public function isHandled(): bool
    {
        return $this->handled;
    }
}
