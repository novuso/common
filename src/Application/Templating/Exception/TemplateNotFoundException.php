<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Templating\Exception;

use Throwable;

/**
 * Class TemplateNotFoundException
 */
class TemplateNotFoundException extends TemplatingException
{
    /**
     * Constructs TemplateNotFoundException
     */
    public function __construct(
        string $message,
        protected ?string $template = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, 0, $previous);
    }

    /**
     * Creates exception for a given template name
     */
    public static function fromName(
        string $name,
        ?Throwable $previous = null
    ): static {
        $message = sprintf('Template not found: %s', $name);

        return new static($message, $name, $previous);
    }

    /**
     * Returns the template name
     */
    public function getTemplate(): ?string
    {
        return $this->template;
    }
}
