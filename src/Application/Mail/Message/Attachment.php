<?php declare(strict_types=1);

namespace Novuso\Common\Application\Mail\Message;

/**
 * Interface Attachment
 */
interface Attachment
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
     * @return string|resource
     */
    public function getBody();

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
