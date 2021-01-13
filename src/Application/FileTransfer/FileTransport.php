<?php

declare(strict_types=1);

namespace Novuso\Common\Application\FileTransfer;

use Novuso\Common\Application\FileTransfer\Exception\FileTransferException;

/**
 * Interface FileTransport
 */
interface FileTransport
{
    /**
     * Sends a file
     *
     * @param string|resource $contents The file contents
     *
     * @throws FileTransferException When error occurs
     */
    public function sendFile(string $path, mixed $contents): void;

    /**
     * Retrieves file contents as a string
     *
     * @throws FileTransferException When error occurs
     */
    public function retrieveFileContents(string $path): string;

    /**
     * Retrieves file contents as a stream resource
     *
     * @return resource
     *
     * @throws FileTransferException When error occurs
     */
    public function retrieveFileResource(string $path): mixed;

    /**
     * Reads the contents of a directory path
     *
     * @return iterable<Resource>
     *
     * @throws FileTransferException When error occurs
     */
    public function readDirectory(string $directory): iterable;
}
