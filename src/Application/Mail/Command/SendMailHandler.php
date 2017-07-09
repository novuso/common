<?php declare(strict_types=1);

namespace Novuso\Common\Application\Mail\Command;

use Novuso\Common\Application\Mail\MailFactory;
use Novuso\Common\Application\Mail\MailService;
use Novuso\Common\Application\Mail\Message;
use Novuso\Common\Application\Mail\Priority;
use Novuso\Common\Domain\Messaging\Command\CommandHandler;
use Novuso\Common\Domain\Messaging\Command\CommandMessage;

/**
 * SendMailHandler handles the SendMailCommand
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
     * Mail factory
     *
     * @var MailFactory
     */
    protected $mailFactory;

    /**
     * Constructs SendMailHandler
     *
     * @param MailService $mailService The mail service
     * @param MailFactory $mailFactory The mail factory
     */
    public function __construct(MailService $mailService, MailFactory $mailFactory)
    {
        $this->mailService = $mailService;
        $this->mailFactory = $mailFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(CommandMessage $message): void
    {
        /** @var SendMailCommand $command */
        $command = $message->payload();

        $message = Message::create();

        if ($command->subject() !== null) {
            $message->setSubject($command->subject());
        }

        foreach ($command->from() as $item) {
            $message->addFrom($item['address'], $item['name']);
        }

        foreach ($command->to() as $item) {
            $message->addTo($item['address'], $item['name']);
        }

        foreach ($command->replyTo() as $item) {
            $message->addReplyTo($item['address'], $item['name']);
        }

        foreach ($command->cc() as $item) {
            $message->addCc($item['address'], $item['name']);
        }

        foreach ($command->bcc() as $item) {
            $message->addBcc($item['address'], $item['name']);
        }

        foreach ($command->content() as $item) {
            $message->addContent(
                base64_decode($item['content']),
                $item['content_type'],
                $item['charset']
            );
        }

        if ($command->sender() !== null) {
            $sender = $command->sender();
            $message->setSender($sender['address'], $sender['name']);
        }

        if ($command->returnPath() !== null) {
            $message->setReturnPath($command->returnPath());
        }

        if ($command->charset() !== null) {
            $message->setCharset($command->charset());
        }

        if ($command->priority() !== null) {
            /** @var Priority $priority */
            $priority = Priority::fromName($command->priority());
            $message->setPriority($priority);
        }

        if ($command->timestamp() !== null) {
            $message->setTimestamp($command->timestamp());
        }

        if ($command->maxLineLength() !== null) {
            $message->setMaxLineLength($command->maxLineLength());
        }

        foreach ($command->attachments() as $item) {
            // pass the attachment ID if the disposition is inline
            if ($item['disposition'] === 'inline') {
                $attachment = $this->mailFactory->createAttachmentFromString(
                    base64_decode($item['body']),
                    $item['file_name'],
                    $item['content_type'],
                    $item['id']
                );
            } else {
                $attachment = $this->mailFactory->createAttachmentFromString(
                    base64_decode($item['body']),
                    $item['file_name'],
                    $item['content_type']
                );
            }
            $message->addAttachment($attachment);
        }

        $this->mailService->send($message);
    }
}
