<?php declare(strict_types=1);

namespace Novuso\Common\Application\Filesystem;

use Novuso\Common\Application\Filesystem\Exception\FilesystemException;

/**
 * Interface Filesystem
 */
interface Filesystem
{
    /**
     * Creates directories recursively
     *
     * @throws FilesystemException When an error occurs
     */
    public function mkdir(string|iterable $dirs, int $mode = 0775): void;

    /**
     * Sets access and modification times
     *
     * @throws FilesystemException When an error occurs
     */
    public function touch(string|iterable $files, ?int $time = null, ?int $atime = null): void;

    /**
     * Renames a file or directory
     *
     * @throws FilesystemException When an error occurs
     */
    public function rename(string $origin, string $target, bool $override = false): void;

    /**
     * Creates a symbolic link
     *
     * @throws FilesystemException When an error occurs
     */
    public function symlink(string $origin, string $target, bool $copyOnWindows = false): void;

    /**
     * Copies a file
     *
     * @throws FilesystemException When an error occurs
     */
    public function copy(string $originFile, string $targetFile, bool $override = false): void;

    /**
     * Mirrors a directory
     *
     * Override will overwrite existing targets.
     *
     * Delete will remove files that are not in the origin directory.
     *
     * @throws FilesystemException When an error occurs
     */
    public function mirror(
        string $originDir,
        string $targetDir,
        bool $override = false,
        bool $delete = false,
        bool $copyOnWindows = false
    ): void;

    /**
     * Checks for the existence of files or directories
     */
    public function exists(string|iterable $paths): bool;

    /**
     * Removes files or directories
     *
     * @throws FilesystemException When an error occurs
     */
    public function remove(string|iterable $paths): void;

    /**
     * Retrieves file contents
     *
     * @throws FilesystemException When an error occurs
     */
    public function get(string $path): string;

    /**
     * Writes file contents
     *
     * @throws FilesystemException When an error occurs
     */
    public function put(string $path, string $content): void;

    /**
     * Checks if a path is a file
     */
    public function isFile(string $path): bool;

    /**
     * Checks if a path is a directory
     */
    public function isDir(string $path): bool;

    /**
     * Checks if a path is a link
     */
    public function isLink(string $path): bool;

    /**
     * Checks if a path is readable
     */
    public function isReadable(string $path): bool;

    /**
     * Checks if a path is writable
     */
    public function isWritable(string $path): bool;

    /**
     * Checks if a path is executable
     */
    public function isExecutable(string $path): bool;

    /**
     * Checks if a path is absolute
     */
    public function isAbsolute(string $path): bool;

    /**
     * Retrieves the last modified timestamp of a file
     *
     * @throws FilesystemException When an error occurs
     */
    public function lastModified(string $path): int;

    /**
     * Retrieves the last accessed timestamp of a file
     *
     * @throws FilesystemException When an error occurs
     */
    public function lastAccessed(string $path): int;

    /**
     * Retrieves the size of a file in bytes
     *
     * @throws FilesystemException When an error occurs
     */
    public function fileSize(string $path): int;

    /**
     * Retrieves the name of a file without extension
     *
     * @throws FilesystemException When an error occurs
     */
    public function fileName(string $path): string;

    /**
     * Retrieves the extension name of a file
     *
     * @throws FilesystemException When an error occurs
     */
    public function fileExt(string $path): string;

    /**
     * Retrieves the directory name of a path
     *
     * @throws FilesystemException When an error occurs
     */
    public function dirName(string $path): string;

    /**
     * Retrieves the base name of a path
     *
     * @throws FilesystemException When an error occurs
     */
    public function baseName(string $path, ?string $suffix = null): string;

    /**
     * Retrieves the file type
     *
     * @throws FilesystemException When an error occurs
     */
    public function fileType(string $path): string;

    /**
     * Retrieves the MIME type of a file
     *
     * @throws FilesystemException When an error occurs
     */
    public function mimeType(string $path): string;

    /**
     * Retrieves the return value of a PHP script
     *
     * @throws FilesystemException When an error occurs
     */
    public function getReturn(string $path): mixed;

    /**
     * Requires a PHP script once
     *
     * @throws FilesystemException When an error occurs
     */
    public function requireOnce(string $path): void;

    /**
     * Changes mode of files or directories
     *
     * @throws FilesystemException When an error occurs
     */
    public function chmod(string|iterable $paths, int $mode, int $umask = 0000, bool $recursive = false): void;

    /**
     * Changes the owner of files or directories
     *
     * @throws FilesystemException When an error occurs
     */
    public function chown(string|iterable $paths, string $user, bool $recursive = false): void;

    /**
     * Changes the group of files or directories
     *
     * @throws FilesystemException When an error occurs
     */
    public function chgrp(string|iterable $paths, string $group, bool $recursive = false): void;
}
