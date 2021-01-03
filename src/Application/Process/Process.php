<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Process;

use Closure;

/**
 * Class Process
 */
final class Process
{
    /** @var resource|string|null */
    protected mixed $input = null;
    protected bool $outputDisabled = false;

    /**
     * Constructs Process
     *
     * @param resource|string|null $input The input
     */
    public function __construct(
        protected string $command,
        protected ?string $directory = null,
        protected ?array $environment = null,
        protected ?float $timeout = null,
        protected ?Closure $stdout = null,
        protected ?Closure $stderr = null,
        mixed $input = null
    ) {
        if ($input !== null) {
            if (is_resource($input)) {
                $this->input = $input;
            } else {
                $this->input = (string) $input;
            }
        }
    }

    /**
     * Retrieves the command
     */
    public function command(): string
    {
        return $this->command;
    }

    /**
     * Retrieves the directory
     */
    public function directory(): ?string
    {
        return $this->directory;
    }

    /**
     * Retrieves the environment
     */
    public function environment(): ?array
    {
        return $this->environment;
    }

    /**
     * Retrieves the input
     *
     * @return resource|string|null
     */
    public function input(): mixed
    {
        return $this->input;
    }

    /**
     * Retrieves the timeout
     */
    public function timeout(): ?float
    {
        return $this->timeout;
    }

    /**
     * Retrieves STDOUT callback
     */
    public function stdout(): ?Closure
    {
        return $this->stdout;
    }

    /**
     * Retrieves STDERR callback
     */
    public function stderr(): ?Closure
    {
        return $this->stderr;
    }

    /**
     * Disables output fetching
     */
    public function disableOutput(): Process
    {
        $this->outputDisabled = true;

        return $this;
    }

    /**
     * Enables output fetching
     */
    public function enableOutput(): Process
    {
        $this->outputDisabled = false;

        return $this;
    }

    /**
     * Checks if output is disabled
     */
    public function isOutputDisabled(): bool
    {
        return $this->outputDisabled;
    }
}
