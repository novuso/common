<?php declare(strict_types=1);

namespace Novuso\Common\Application\Mail;

use Novuso\Common\Application\Mail\Message\Attachment;
use Novuso\Common\Application\Mail\Message\MailFactory;
use Novuso\Common\Application\Mail\Message\Message;
use Novuso\Common\Application\Mail\Transport\Mailer;

/**
 * MailService is an application mail service
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class MailService implements Mailer, MailFactory
{
    /**
     * Mailer
     *
     * @var Mailer
     */
    protected $mailer;

    /**
     * Mail factory
     *
     * @var MailFactory
     */
    protected $factory;

    /**
     * Constructs MailService
     *
     * @param Mailer      $mailer  The mailer
     * @param MailFactory $factory The mail factory
     */
    public function __construct(Mailer $mailer, MailFactory $factory)
    {
        $this->mailer = $mailer;
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public function send(Message $message): void
    {
        $this->mailer->send($message);
    }

    /**
     * {@inheritdoc}
     */
    public function createMessage(): Message
    {
        return $this->factory->createMessage();
    }

    /**
     * {@inheritdoc}
     */
    public function createAttachmentFromString(
        string $body,
        string $fileName,
        string $contentType,
        ?string $embedId = null
    ): Attachment {
        return $this->factory->createAttachmentFromString($body, $fileName, $contentType, $embedId);
    }

    /**
     * {@inheritdoc}
     */
    public function createAttachmentFromPath(
        string $path,
        string $fileName,
        string $contentType,
        ?string $embedId = null
    ): Attachment {
        return $this->factory->createAttachmentFromPath($path, $fileName, $contentType, $embedId);
    }

    /**
     * {@inheritdoc}
     */
    public function generateEmbedId(): string
    {
        return $this->factory->generateEmbedId();
    }
}
