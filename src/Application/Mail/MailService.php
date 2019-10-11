<?php declare(strict_types=1);

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
     * Mail transport
     *
     * @var MailTransport
     */
    protected $transport;

    /**
     * Mail factory
     *
     * @var MailFactory
     */
    protected $factory;

    /**
     * Constructs MailService
     *
     * @param MailTransport $transport The mail transport
     * @param MailFactory   $factory   The mail factory
     */
    public function __construct(MailTransport $transport, MailFactory $factory)
    {
        $this->transport = $transport;
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public function send(MailMessage $message): void
    {
        $this->transport->send($message);
    }

    /**
     * {@inheritdoc}
     */
    public function createMessage(): MailMessage
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
