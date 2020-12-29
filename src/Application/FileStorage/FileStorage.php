<?php declare(strict_types=1);

namespace Novuso\Common\Application\FileStorage;

use Novuso\Common\Application\FileStorage\Exception\FileStorageException;
use Novuso\Common\Domain\Value\DateTime\DateTime;

/**
 * Interface FileStorage
 */
interface FileStorage
{
    /**
     * Adds a file to storage
     *
     * @param string|resource $contents The file contents
     *
     * @throws FileStorageException When error occurs
     */
    public function putFile(string $path, mixed $contents): void;

    /**
     * Retrieves file contents as a string
     *
     * @throws FileStorageException When error occurs
     */
    public function getFileContents(string $path): string;

    /**
     * Retrieves file contents as a stream resource
     *
     * @return resource
     *
     * @throws FileStorageException When error occurs
     */
    public function getFileResource(string $path): mixed;

    /**
     * Checks if a file exists in storage
     *
     * @throws FileStorageException When error occurs
     */
    public function hasFile(string $path): bool;

    /**
     * Removes a file from storage
     *
     * @throws FileStorageException When error occurs
     */
    public function removeFile(string $path): void;

    /**
     * Copies a file to another location in storage
     *
     * @throws FileStorageException When error occurs
     */
    public function copyFile(string $source, string $destination): void;

    /**
     * Moves a file to another location in storage
     *
     * @throws FileStorageException When error occurs
     */
    public function moveFile(string $source, string $destination): void;

    /**
     * Retrieves the file size in bytes
     *
     * @throws FileStorageException When error occurs
     */
    public function size(string $path): int;

    /**
     * Retrieves the last modified date/time
     *
     * @throws FileStorageException When error occurs
     */
    public function lastModified(string $path): DateTime;

    /**
     * Retrieves a list of files in a given path
     *
     * @throws FileStorageException When error occurs
     */
    public function listFiles(?string $path = null): array;

    /**
     * Retrieves a list of files in a given path recursively
     *
     * @throws FileStorageException When error occurs
     */
    public function listFilesRecursively(?string $path = null): array;

    /**
     * Retrieves a list of directories in a given path
     *
     * @throws FileStorageException When error occurs
     */
    public function listDirectories(?string $path = null): array;

    /**
     * Retrieves a list of directories in a given path recursively
     *
     * @throws FileStorageException When error occurs
     */
    public function listDirectoriesRecursively(?string $path = null): array;
}
