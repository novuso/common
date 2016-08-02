<?php declare(strict_types=1);

namespace Novuso\Common\Application\Mail;

/**
 * MailFactory is the interface for an mail factory
 *
 * @copyright Copyright (c) 2016, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface MailFactory
{
    /**
     * Creates a mail message
     *
     * @return Message
     */
    public function createMessage(): Message;

    /**
     * Creates an attachment from a data string
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
        string $embedId = null
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
        string $embedId = null
    ): Attachment;

    /**
     * Generates a random embed ID
     *
     * @return string
     */
    public function generateEmbedId(): string;
}
