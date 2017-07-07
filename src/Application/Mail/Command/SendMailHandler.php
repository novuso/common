<?php declare(strict_types=1);

namespace Novuso\Common\Application\Mail\Command;

use Novuso\Common\Application\Mail\MailService;
use Novuso\Common\Domain\Messaging\Command\CommandHandler;
use Novuso\Common\Domain\Messaging\Command\CommandMessage;

/**
 * SendMailHandler handles the SendMailCommand
 *
 * Attachments are not supported by this handler
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class SendMailHandler implements CommandHandler
{
    /**
     * Mail service
     *
     * @var MailService
     */
    protected $mailService;

    /**
     * Constructs SendMailHandler
     *
     * @param MailService $mailService The mail service
     */
    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(CommandMessage $message): void
    {
        /** @var SendMailCommand $command */
        $command = $message->payload();
        $message = $command->getMessage();
        $this->mailService->send($message);
    }
}
