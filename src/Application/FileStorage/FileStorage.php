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
     * @param string          $path     The file path
     * @param string|resource $contents The file contents
     *
     * @return void
     *
     * @throws FileStorageException When error occurs
     */
    public function putFile(string $path, $contents): void;

    /**
     * Retrieves file contents as a string
     *
     * @param string $path The file path
     *
     * @return string
     *
     * @throws FileStorageException When error occurs
     */
    public function getFileContents(string $path): string;

    /**
     * Retrieves file contents as a stream resource
     *
     * @param string $path The file path
     *
     * @return resource
     *
     * @throws FileStorageException When error occurs
     */
    public function getFileResource(string $path);

    /**
     * Checks if a file exists in storage
     *
     * @param string $path The file path
     *
     * @return bool
     *
     * @throws FileStorageException When error occurs
     */
    public function hasFile(string $path): bool;

    /**
     * Removes a file from storage
     *
     * @param string $path The file path
     *
     * @return void
     *
     * @throws FileStorageException When error occurs
     */
    public function removeFile(string $path): void;

    /**
     * Copies a file to another location in storage
     *
     * @param string $source      The source path
     * @param string $destination The destination path
     *
     * @return void
     *
     * @throws FileStorageException When error occurs
     */
    public function copyFile(string $source, string $destination): void;

    /**
     * Moves a file to another location in storage
     *
     * @param string $source      The source path
     * @param string $destination The destination path
     *
     * @return void
     *
     * @throws FileStorageException When error occurs
     */
    public function moveFile(string $source, string $destination): void;

    /**
     * Retrieves the file size in bytes
     *
     * @param string $path The file path
     *
     * @return int
     *
     * @throws FileStorageException When error occurs
     */
    public function size(string $path): int;

    /**
     * Retrieves the last modified date/time
     *
     * @param string $path The file path
     *
     * @return DateTime
     *
     * @throws FileStorageException When error occurs
     */
    public function lastModified(string $path): DateTime;

    /**
     * Retrieves a list of files in a given path
     *
     * @param string $path The directory path
     *
     * @return string[]
     *
     * @throws FileStorageException When error occurs
     */
    public function listFiles(?string $path = null): array;

    /**
     * Retrieves a list of files in a given path recursively
     *
     * @param string $path The directory path
     *
     * @return string[]
     *
     * @throws FileStorageException When error occurs
     */
    public function listFilesRecursively(?string $path = null): array;

    /**
     * Retrieves a list of directories in a given path
     *
     * @param string $path The directory path
     *
     * @return string[]
     *
     * @throws FileStorageException When error occurs
     */
    public function listDirectories(?string $path = null): array;

    /**
     * Retrieves a list of directories in a given path recursively
     *
     * @param string $path The directory path
     *
     * @return string[]
     *
     * @throws FileStorageException When error occurs
     */
    public function listDirectoriesRecursively(?string $path = null): array;
}
