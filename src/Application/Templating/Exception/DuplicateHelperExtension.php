<?php declare(strict_types=1);

namespace Novuso\Common\Application\Templating\Exception;

use Exception;
use Novuso\System\Exception\RuntimeException;

/**
 * DuplicateHelperExtension is thrown when a helper name is duplicated
 *
 * @copyright Copyright (c) 2016, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class DuplicateHelperExtension extends RuntimeException
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
     * @param Exception|null $previous The previous exception
     */
    public function __construct(string $message, string $name = null, Exception $previous = null)
    {
        $this->name = $name;
        parent::__construct($message, 0, $previous);
    }

    /**
     * Creates exception for a given helper name
     *
     * @param string         $name     The helper name
     * @param Exception|null $previous The previous exception
     *
     * @return DuplicateHelperExtension
     */
    public static function fromName(string $name, Exception $previous = null): DuplicateHelperExtension
    {
        $message = sprintf('Duplicate helper: %s', $name);

        return new static($message, $name, $previous);
    }

    /**
     * Returns the helper name
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }
}
