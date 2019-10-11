<?php declare(strict_types=1);

namespace Novuso\Common\Application\Templating\Exception;

use Throwable;

/**
 * Class TemplateNotFoundException
 */
class TemplateNotFoundException extends TemplatingException
{
    /**
     * Template name
     *
     * @var string|null
     */
    protected $template;

    /**
     * Constructs TemplateNotFoundException
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
