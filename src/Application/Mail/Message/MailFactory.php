<?php declare(strict_types=1);

namespace Novuso\Common\Application\Mail\Message;

/**
 * Interface MailFactory
 */
interface MailFactory
{
    /**
     * Creates a mail message
     */
    public function createMessage(): MailMessage;

    /**
     * Creates an attachment from a content string
     */
    public function createAttachmentFromString(
        string $body,
        string $fileName,
        string $contentType,
        ?string $embedId = null
    ): Attachment;

    /**
     * Creates an attachment from a local file path
     */
    public function createAttachmentFromPath(
        string $path,
        string $fileName,
        string $contentType,
        ?string $embedId = null
    ): Attachment;

    /**
     * Generates an embed ID for inline attachments
     */
    public function generateEmbedId(): string;
}
