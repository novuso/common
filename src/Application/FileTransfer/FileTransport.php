<?php declare(strict_types=1);

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
     * @param string          $path     The remote path
     * @param string|resource $contents The file contents
     *
     * @return void
     *
     * @throws FileTransferException When error occurs
     */
    public function sendFile(string $path, $contents): void;

    /**
     * Retrieves file contents as a string
     *
     * @param string $path The remote path
     *
     * @return string
     *
     * @throws FileTransferException When error occurs
     */
    public function retrieveFileContents(string $path): string;

    /**
     * Retrieves file contents as a stream resource
     *
     * @param string $path The remote path
     *
     * @return resource
     *
     * @throws FileTransferException When error occurs
     */
    public function retrieveFileResource(string $path);

    /**
     * Reads the contents of a directory path
     *
     * @param string $directory The remote directory path
     *
     * @return iterable|Resource[]
     *
     * @throws FileTransferException When error occurs
     */
    public function readDirectory(string $directory): iterable;
}
