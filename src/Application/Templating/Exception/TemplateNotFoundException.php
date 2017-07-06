<?php declare(strict_types=1);

namespace Novuso\Common\Application\Templating\Exception;

use Throwable;

/**
 * TemplateNotFoundException is thrown when a template is not found
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class TemplateNotFoundException extends TemplatingException
{
    /**
     * Creates exception for a given template name
     *
     * @param string         $name     The template name
     * @param Throwable|null $previous The previous exception
     *
     * @return TemplateNotFoundException
     */
    public static function fromName(string $name, ?Throwable $previous = null): TemplateNotFoundException
    {
        $message = sprintf('Template not found: %s', $name);

        return new static($message, $name, $previous);
    }
}
