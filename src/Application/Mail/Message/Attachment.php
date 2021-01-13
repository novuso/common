<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Mail\Message;

/**
 * Interface Attachment
 */
interface Attachment
{
    /**
     * Retrieves the content ID
     */
    public function getId(): string;

    /**
     * Retrieves the attachment body
     *
     * @return string|resource
     */
    public function getBody(): mixed;

    /**
     * Retrieves the file name
     */
    public function getFileName(): string;

    /**
     * Retrieves the content type
     */
    public function getContentType(): string;

    /**
     * Retrieves the content disposition
     */
    public function getDisposition(): string;

    /**
     * Retrieves the CID source for embedding
     */
    public function embed(): string;
}
