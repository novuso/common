<?php declare(strict_types=1);

namespace Novuso\Common\Test\Resources\Domain\Messaging\Command;

use Exception;
use Novuso\Common\Domain\Messaging\Command\CommandHandler;
use Novuso\Common\Domain\Messaging\Command\CommandMessage;

/**
 * Class ErrorUserHandler
 */
class ErrorUserHandler implements CommandHandler
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
