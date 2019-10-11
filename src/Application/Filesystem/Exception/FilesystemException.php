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
     * Filesystem path
     *
     * @var string|null
     */
    protected $path;

    /**
     * Constructs FilesystemException
     *
     * @param string         $message  The message
     * @param string|null    $path     The filesystem path
     * @param Throwable|null $previous The previous exception
     */
    public function __construct($message = "", ?string $path = null, ?Throwable $previous = null)
    {
        $this->path = $path;
        parent::__construct($message, 0, $previous);
    }

    /**
     * Retrieves the filesystem path
     *
     * @return string|null
     */
    public function getPath(): ?string
    {
        return $this->path;
    }
}
