<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Mail;

use Novuso\Common\Application\Mail\Message\Attachment;
use Novuso\Common\Application\Mail\Message\MailFactory;
use Novuso\Common\Application\Mail\Message\MailMessage;
use Novuso\Common\Application\Mail\Transport\MailTransport;

/**
 * Class MailService
 */
final class MailService implements MailTransport, MailFactory
{
    /**
     * Constructs MailService
     */
    public function __construct(
        protected MailTransport $transport,
        protected MailFactory $factory
    ) {
    }

    /**
     * @inheritDoc
     */
    public function send(MailMessage $message): void
    {
        $this->transport->send($message);
    }

    /**
     * @inheritDoc
     */
    public function createMessage(): MailMessage
    {
        return $this->factory->createMessage();
    }

    /**
     * @inheritDoc
     */
    public function createAttachmentFromString(
        string $body,
        string $fileName,
        string $contentType,
        ?string $embedId = null
    ): Attachment {
        return $this->factory->createAttachmentFromString(
            $body,
            $fileName,
            $contentType,
            $embedId
        );
    }

    /**
     * @inheritDoc
     */
    public function createAttachmentFromPath(
        string $path,
        string $fileName,
        string $contentType,
        ?string $embedId = null
    ): Attachment {
        return $this->factory->createAttachmentFromPath(
            $path,
            $fileName,
            $contentType,
            $embedId
        );
    }

    /**
     * @inheritDoc
     */
    public function generateEmbedId(): string
    {
        return $this->factory->generateEmbedId();
    }
}
