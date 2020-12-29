<?php declare(strict_types=1);

namespace Novuso\Common\Application\Filesystem\Exception;

use Novuso\System\Exception\SystemException;
use Throwable;

/**
 * Class FilesystemException
 */
class FilesystemException extends SystemException
{
    /**
     * Constructs FilesystemException
     */
    public function __construct(string $message = '', protected ?string $path = null, ?Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }

    /**
     * Retrieves the filesystem path
     */
    public function getPath(): ?string
    {
        return $this->path;
    }
}
