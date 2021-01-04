<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Templating\Exception;

use Throwable;

/**
 * Class DuplicateHelperException
 */
class DuplicateHelperException extends TemplatingException
{
    /**
     * Constructs DuplicateHelperException
     */
    public function __construct(
        string $message,
        protected ?string $name = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, 0, $previous);
    }

    /**
     * Creates exception for a given helper name
     */
    public static function fromName(
        string $name,
        ?Throwable $previous = null
    ): static {
        $message = sprintf('Duplicate helper: %s', $name);

        return new static($message, $name, $previous);
    }

    /**
     * Returns the helper name
     */
    public function getName(): ?string
    {
        return $this->name;
    }
}
