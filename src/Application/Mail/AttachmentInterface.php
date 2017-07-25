<?php declare(strict_types=1);

namespace Novuso\Common\Application\Mail;

/**
 * AttachmentInterface is the interface for a message attachment
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface AttachmentInterface
{
    /**
     * Retrieves the content ID
     *
     * @return string
     */
    public function getId(): string;

    /**
     * Retrieves the attachment body
     *
     * @return string
     */
    public function getBody(): string;

    /**
     * Retrieves the file name
     *
     * @return string
     */
    public function getFileName(): string;

    /**
     * Retrieves the content type
     *
     * @return string
     */
    public function getContentType(): string;

    /**
     * Retrieves the content disposition
     *
     * @return string
     */
    public function getDisposition(): string;

    /**
     * Retrieves the CID source for embedding
     *
     * @return string
     */
    public function embed(): string;
}
