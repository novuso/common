<?php declare(strict_types=1);

namespace Novuso\Common\Application\Filesystem\Exception;

use Novuso\System\Exception\RuntimeException;
use Throwable;

/**
 * FilesystemException is thrown when a filesystem error occurs
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class FilesystemException extends RuntimeException
{
    /**
     * File path
     *
     * @var string|null
     */
    protected $path;

    /**
     * Constructs FilesystemException
     *
     * @param string         $message  The message
     * @param string|null    $path     The file path
     * @param Throwable|null $previous The previous exception
     */
    public function __construct(string $message, ?string $path = null, ?Throwable $previous = null)
    {
        $this->path = $path;
        parent::__construct($message, 0, $previous);
    }

    /**
     * Returns the file path
     *
     * @return string|null
     */
    public function getPath()
    {
        return $this->path;
    }
}
