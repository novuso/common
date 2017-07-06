<?php declare(strict_types=1);

namespace Novuso\Common\Application\Templating\Exception;

use Novuso\System\Exception\RuntimeException;
use Throwable;

/**
 * DuplicateHelperException is thrown when a helper name is duplicated
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class DuplicateHelperException extends RuntimeException
{
    /**
     * Helper name
     *
     * @var string|null
     */
    protected $name;

    /**
     * Constructs ServiceContainerException
     *
     * @param string         $message  The message
     * @param string|null    $name     The helper name
     * @param Throwable|null $previous The previous exception
     */
    public function __construct(string $message, ?string $name = null, ?Throwable $previous = null)
    {
        $this->name = $name;
        parent::__construct($message, 0, $previous);
    }

    /**
     * Creates exception for a given helper name
     *
     * @param string         $name     The helper name
     * @param Throwable|null $previous The previous exception
     *
     * @return DuplicateHelperException
     */
    public static function fromName(string $name, ?Throwable $previous = null): DuplicateHelperException
    {
        $message = sprintf('Duplicate helper: %s', $name);

        return new static($message, $name, $previous);
    }

    /**
     * Returns the helper name
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }
}
