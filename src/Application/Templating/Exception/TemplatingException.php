<?php declare(strict_types=1);

namespace Novuso\Common\Application\Templating\Exception;

use Novuso\System\Exception\SystemException;
use Throwable;

/**
 * TemplatingException is thrown for template engine errors
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class TemplatingException extends SystemException
{
    /**
     * Template name
     *
     * @var string|null
     */
    protected $template;

    /**
     * Constructs TemplatingException
     *
     * @param string         $message  The message
     * @param string|null    $template The template name
     * @param Throwable|null $previous The previous exception
     */
    public function __construct(string $message, ?string $template = null, ?Throwable $previous = null)
    {
        $this->template = $template;
        parent::__construct($message, 0, $previous);
    }

    /**
     * Returns the template name
     *
     * @return string|null
     */
    public function getTemplate(): ?string
    {
        return $this->template;
    }
}
