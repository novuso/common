<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Process;

use Closure;
use Novuso\System\Exception\DomainException;
use Novuso\System\Exception\MethodCallException;

/**
 * Class ProcessBuilder
 */
final class ProcessBuilder
{
    /** @var resource|string|null */
    protected mixed $input = null;
    protected array $prefix = [];
    protected array $arguments = [];
    protected ?string $directory = null;
    protected ?float $timeout = null;
    protected bool $inheritEnv = true;
    protected array $environment = [];
    protected ?Closure $stdout = null;
    protected ?Closure $stderr = null;
    protected bool $outputDisabled = false;

    /**
     * Constructs ProcessBuilder
     */
    public function __construct(string|array|null $arguments = null)
    {
        if ($arguments === null) {
            $arguments = [];
        } else {
            $arguments = (array) $arguments;
        }

        foreach ($arguments as $arg) {
            $this->arg($arg);
        }
    }

    /**
     * Creates instance with arguments
     */
    public static function create(
        string|array|null $arguments = null
    ): ProcessBuilder {
        return new static($arguments);
    }

    /**
     * Sets the prefix
     */
    public function prefix(string|array $prefix): static
    {
        $this->prefix = (array) $prefix;

        return $this;
    }

    /**
     * Adds an argument
     */
    public function arg(string $arg): static
    {
        if ($arg === '') {
            return $this;
        }

        $this->arguments[] = $arg;

        return $this;
    }

    /**
     * Adds an option
     */
    public function option(string $option, ?string $value = null): static
    {
        if ($option === '') {
            return $this;
        }

        if (!str_starts_with($option, '-')) {
            $option = sprintf('--%s', $option);
        }

        $this->arguments[] = $option;

        if ($value !== null) {
            $this->arguments[] = $value;
        }

        return $this;
    }

    /**
     * Adds a short option
     */
    public function short(string $option, ?string $value = null): static
    {
        if ($option === '') {
            return $this;
        }

        if (!str_starts_with($option, '-')) {
            $option = sprintf('-%s', $option);
        }

        $this->arguments[] = $option;

        if ($value !== null) {
            $this->arguments[] = $value;
        }

        return $this;
    }

    /**
     * Clears arguments
     */
    public function clearArgs(): static
    {
        $this->arguments = [];

        return $this;
    }

    /**
     * Sets the working directory
     */
    public function directory(?string $directory = null): static
    {
        $this->directory = $directory;

        return $this;
    }

    /**
     * Sets the input
     *
     * @param resource|string|null $input The input
     */
    public function input(mixed $input): static
    {
        if ($input === null) {
            $this->input = null;

            return $this;
        }

        if (is_resource($input)) {
            $this->input = $input;

            return $this;
        }

        if (is_scalar($input)) {
            $this->input = (string) $input;
        }

        return $this;
    }

    /**
     * Sets the timeout in seconds
     *
     * @throws DomainException When the timeout is invalid
     */
    public function timeout(int|float|null $timeout): static
    {
        if ($timeout === null) {
            $this->timeout = null;

            return $this;
        }

        $timeout = (float) $timeout;

        if ($timeout < 0) {
            throw new DomainException('Timeout must be a positive number');
        }

        $this->timeout = $timeout;

        return $this;
    }

    /**
     * Sets whether to inherit environment variables
     */
    public function inheritEnv(bool $inheritEnv = true): static
    {
        $this->inheritEnv = $inheritEnv;

        return $this;
    }

    /**
     * Sets an environment variable
     */
    public function setEnv(string $name, ?string $value = null): static
    {
        $this->environment[$name] = $value;

        return $this;
    }

    /**
     * Adds a callback to receive STDOUT
     */
    public function stdout(?callable $stdout = null): static
    {
        if ($stdout === null) {
            $this->stdout = null;

            return $this;
        }

        $this->stdout = Closure::fromCallable($stdout);

        return $this;
    }

    /**
     * Adds a callback to receive STDERR
     */
    public function stderr(?callable $stderr = null): static
    {
        if ($stderr === null) {
            $this->stderr = null;

            return $this;
        }

        $this->stderr = Closure::fromCallable($stderr);

        return $this;
    }

    /**
     * Disables output fetching
     */
    public function disableOutput(): static
    {
        $this->outputDisabled = true;

        return $this;
    }

    /**
     * Enables output fetching
     */
    public function enableOutput(): static
    {
        $this->outputDisabled = false;

        return $this;
    }

    /**
     * Creates a Process instance
     *
     * @throws MethodCallException When arguments are not present
     */
    public function getProcess(): Process
    {
        if (count($this->prefix) === 0 && count($this->arguments) === 0) {
            throw new MethodCallException(
                'You must add arguments before calling getProcess()'
            );
        }

        $arguments = array_merge($this->prefix, $this->arguments);
        $command = implode(' ', array_map('escapeshellarg', $arguments));

        if ($this->inheritEnv) {
            $env = array_replace($_SERVER, $this->environment);
        } else {
            $env = $this->environment;
        }

        $process = new Process(
            $command,
            $this->directory,
            $env,
            $this->timeout,
            $this->stdout,
            $this->stderr,
            $this->input
        );

        if ($this->outputDisabled) {
            $process->disableOutput();
        }

        return $process;
    }
}
