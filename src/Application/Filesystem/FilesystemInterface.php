<?php declare(strict_types=1);

namespace Novuso\Common\Application\Filesystem;

use Novuso\Common\Application\Filesystem\Exception\FilesystemException;
use Traversable;

/**
 * FilesystemInterface is the interface for the server filesystem
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface FilesystemInterface
{
    /**
     * Creates directories recursively
     *
     * @param string|array|Traversable $dirs The directory path(s)
     * @param int                      $mode The directory mode (octal)
     *
     * @return void
     *
     * @throws FilesystemException When an error occurs
     */
    public function mkdir($dirs, int $mode = 0775): void;

    /**
     * Sets access and modification times
     *
     * @param string|array|Traversable   $files The file path(s)
     * @param int|null $time  The touch time as a Unix timestamp
     * @param int|null $atime The access time as a Unix timestamp
     *
     * @return void
     *
     * @throws FilesystemException When an error occurs
     */
    public function touch($files, ?int $time = null, ?int $atime = null): void;

    /**
     * Renames a file or directory
     *
     * @param string $origin   The origin file or directory
     * @param string $target   The target file or directory
     * @param bool   $override Whether to overwrite the target if it exists
     *
     * @return void
     *
     * @throws FilesystemException When an error occurs
     */
    public function rename(string $origin, string $target, bool $override = false): void;

    /**
     * Creates a symbolic link
     *
     * @param string $origin        The origin path
     * @param string $target        The target path
     * @param bool   $copyOnWindows Whether to copy files if on Windows
     *
     * @return void
     *
     * @throws FilesystemException When an error occurs
     */
    public function symlink(string $origin, string $target, bool $copyOnWindows = false): void;

    /**
     * Copies a file
     *
     * @param string $originFile The origin file path
     * @param string $targetFile The target file path
     * @param bool   $override   Whether to overwrite the target if it exists
     *
     * @return void
     *
     * @throws FilesystemException When an error occurs
     */
    public function copy(string $originFile, string $targetFile, bool $override = false): void;

    /**
     * Mirrors a directory
     *
     * @param string $originDir     The origin directory path
     * @param string $targetDir     The target directory path
     * @param bool   $override      Whether to overwrite existing targets
     * @param bool   $delete        Whether to delete files that are not in the
     *                              origin directory
     * @param bool   $copyOnWindows Whether to copy files if on Windows
     *
     * @return void
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
     *
     * @param string|array|Traversable $paths File or directory path(s)
     *
     * @return bool
     */
    public function exists($paths): bool;

    /**
     * Removes files or directories
     *
     * @param string|array|Traversable $paths File or directory path(s)
     *
     * @return void
     *
     * @throws FilesystemException When an error occurs
     */
    public function remove($paths): void;

    /**
     * Retrieves file contents
     *
     * @param string $path The file path
     *
     * @return string
     *
     * @throws FilesystemException When an error occurs
     */
    public function get(string $path): string;

    /**
     * Writes file contents
     *
     * @param string $path    The file path
     * @param string $content The content
     *
     * @return void
     *
     * @throws FilesystemException When an error occurs
     */
    public function put(string $path, string $content): void;

    /**
     * Checks if a path is a file
     *
     * @param string $path The path
     *
     * @return bool
     */
    public function isFile(string $path): bool;

    /**
     * Checks if a path is a directory
     *
     * @param string $path The path
     *
     * @return bool
     */
    public function isDir(string $path): bool;

    /**
     * Checks if a path is a link
     *
     * @param string $path The path
     *
     * @return bool
     */
    public function isLink(string $path): bool;

    /**
     * Checks if a path is readable
     *
     * @param string $path The path
     *
     * @return bool
     */
    public function isReadable(string $path): bool;

    /**
     * Checks if a path is writable
     *
     * @param string $path The path
     *
     * @return bool
     */
    public function isWritable(string $path): bool;

    /**
     * Checks if a path is executable
     *
     * @param string $path The path
     *
     * @return bool
     */
    public function isExecutable(string $path): bool;

    /**
     * Checks if a path is absolute
     *
     * @param string $path The path
     *
     * @return bool
     */
    public function isAbsolute(string $path): bool;

    /**
     * Retrieves the last modified timestamp of a file
     *
     * @param string $path The file path
     *
     * @return int
     *
     * @throws FilesystemException When an error occurs
     */
    public function lastModified(string $path): int;

    /**
     * Retrieves the last accessed timestamp of a file
     *
     * @param string $path The file path
     *
     * @return int
     *
     * @throws FilesystemException When an error occurs
     */
    public function lastAccessed(string $path): int;

    /**
     * Retrieves the size of a file in bytes
     *
     * @param string $path The file path
     *
     * @return int
     *
     * @throws FilesystemException When an error occurs
     */
    public function fileSize(string $path): int;

    /**
     * Retrieves the name of a file without extension
     *
     * @param string $path The file path
     *
     * @return string
     *
     * @throws FilesystemException When an error occurs
     */
    public function fileName(string $path): string;

    /**
     * Retrieves the extension name of a file
     *
     * @param string $path The file path
     *
     * @return string
     *
     * @throws FilesystemException When an error occurs
     */
    public function fileExt(string $path): string;

    /**
     * Retrieves the directory name of a path
     *
     * @param string $path The path
     *
     * @return string
     *
     * @throws FilesystemException When an error occurs
     */
    public function dirName(string $path): string;

    /**
     * Retrieves the base name of a path
     *
     * @param string      $path   The path
     * @param string|null $suffix The suffix to remove
     *
     * @return string
     *
     * @throws FilesystemException When an error occurs
     */
    public function baseName(string $path, ?string $suffix = null): string;

    /**
     * Retrieves the file type
     *
     * @param string $path The file path
     *
     * @return string
     *
     * @throws FilesystemException When an error occurs
     */
    public function fileType(string $path): string;

    /**
     * Retrieves the MIME type of a file
     *
     * @param string $path The file path
     *
     * @return string
     *
     * @throws FilesystemException When an error occurs
     */
    public function mimeType(string $path): string;

    /**
     * Retrieves the return value of a PHP script
     *
     * @param string $path The file path
     *
     * @return mixed
     *
     * @throws FilesystemException When an error occurs
     */
    public function getReturn(string $path);

    /**
     * Requires a PHP script once
     *
     * @param string $path The file path
     *
     * @return void
     *
     * @throws FilesystemException When an error occurs
     */
    public function requireOnce(string $path): void;

    /**
     * Changes mode of files or directories
     *
     * @param string|array|Traversable $paths     The file or directory path(s)
     * @param int                      $mode      The new mode (octal)
     * @param int                      $umask     The mode mask (octal)
     * @param bool                     $recursive Whether to change the mode
     *                                            recursively
     *
     * @return void
     *
     * @throws FilesystemException When an error occurs
     */
    public function chmod($paths, int $mode, int $umask = 0000, bool $recursive = false): void;

    /**
     * Changes the owner of files or directories
     *
     * @param string|array|Traversable $paths     The file or directory path(s)
     * @param string                   $user      The new owner username
     * @param bool                     $recursive Whether to change the owner
     *                                            recursively
     *
     * @return void
     *
     * @throws FilesystemException When an error occurs
     */
    public function chown($paths, string $user, bool $recursive = false): void;

    /**
     * Changes the group of files or directories
     *
     * @param string|array|Traversable $paths     The file or directory path(s)
     * @param string                   $group     The new group name
     * @param bool                     $recursive Whether to change the group
     *                                            recursively
     *
     * @return void
     *
     * @throws FilesystemException When an error occurs
     */
    public function chgrp($paths, string $group, bool $recursive = false): void;
}
