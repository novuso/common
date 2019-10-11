<?php declare(strict_types=1);

namespace Novuso\Common\Application\Mail\Message;

/**
 * Interface MailFactory
 */
interface MailFactory
{
    /**
     * Creates a mail message
     *
     * @return MailMessage
     */
    public function createMessage(): MailMessage;

    /**
     * Creates an attachment from a content string
     *
     * @param string      $body        The file contents
     * @param string      $fileName    The file name
     * @param string      $contentType The content type
     * @param string|null $embedId     The embed ID
     *
     * @return Attachment
     */
    public function createAttachmentFromString(
        string $body,
        string $fileName,
        string $contentType,
        ?string $embedId = null
    ): Attachment;

    /**
     * Creates an attachment from a local file path
     *
     * @param string      $path        The local path
     * @param string      $fileName    The file name
     * @param string      $contentType The content type
     * @param string|null $embedId     The embed ID
     *
     * @return Attachment
     */
    public function createAttachmentFromPath(
        string $path,
        string $fileName,
        string $contentType,
        ?string $embedId = null
    ): Attachment;

    /**
     * Generates an embed ID for inline attachments
     *
     * @return string
     */
    public function generateEmbedId(): string;
}
