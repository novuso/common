<?php declare(strict_types=1);

namespace Novuso\Common\Application\Filesystem\Exception;

use Throwable;

/**
 * Class FileNotFoundException
 */
class FileNotFoundException extends FilesystemException
{
    /**
     * Creates exception for a given path
     *
     * @param string         $path     The file path
     * @param Throwable|null $previous The previous exception
     *
     * @return FileNotFoundException
     */
    public static function fromPath(string $path, ?Throwable $previous = null): FileNotFoundException
    {
        $message = sprintf('File not found: %s', $path);

        return new static($message, $path, $previous);
    }
}
