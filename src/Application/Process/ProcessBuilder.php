<?php declare(strict_types=1);

namespace Novuso\Common\Application\Process;

use Novuso\System\Exception\DomainException;
use Novuso\System\Exception\MethodCallException;

/**
 * Class ProcessBuilder
 */
final class ProcessBuilder
{
    /**
     * Command prefix
     *
     * @var array
     */
    protected $prefix = [];

    /**
     * Command arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * Working directory
     *
     * @var string|null
     */
    protected $directory;

    /**
     * Command input
     *
     * @var resource|string|null
     */
    protected $input;

    /**
     * Command timeout
     *
     * @var float|null
     */
    protected $timeout;

    /**
     * Inherit environment status
     *
     * @var bool
     */
    protected $inheritEnv = true;

    /**
     * Environment variables
     *
     * @var array
     */
    protected $environment = [];

    /**
     * Callback for STDOUT
     *
     * @var callable|null
     */
    protected $stdout;

    /**
     * Callback for STDERR
     *
     * @var callable|null
     */
    protected $stderr;

    /**
     * Output disabled status
     *
     * @var bool
     */
    protected $outputDisabled = false;

    /**
     * Constructs ProcessBuilder
     *
     * @param string|array|null $arguments Argument or list of arguments
     */
    public function __construct($arguments = null)
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
     *
     * @param string|array|null $arguments Argument or list of arguments
     *
     * @return ProcessBuilder
     */
    public static function create($arguments = null): ProcessBuilder
    {
        return new static($arguments);
    }

    /**
     * Sets the prefix
     *
     * @param string|array $prefix A command prefix or array of prefixes
     *
     * @return ProcessBuilder
     */
    public function prefix($prefix): ProcessBuilder
    {
        $this->prefix = (array) $prefix;

        return $this;
    }

    /**
     * Adds an argument
     *
     * @param string $arg The command argument
     *
     * @return ProcessBuilder
     */
    public function arg(string $arg): ProcessBuilder
    {
        if ($arg === '') {
            return $this;
        }

        $this->arguments[] = $arg;

        return $this;
    }

    /**
     * Adds an option
     *
     * @param string      $option The command option
     * @param string|null $value  The option value
     *
     * @return ProcessBuilder
     */
    public function option(string $option, ?string $value = null): ProcessBuilder
    {
        if ($option === '') {
            return $this;
        }

        if (strpos($option, '-') !== 0) {
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
     *
     * @param string      $option The short option
     * @param string|null $value  The option value
     *
     * @return ProcessBuilder
     */
    public function short(string $option, ?string $value = null): ProcessBuilder
    {
        if ($option === '') {
            return $this;
        }

        if (strpos($option, '-') !== 0) {
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
     *
     * @return ProcessBuilder
     */
    public function clearArgs(): ProcessBuilder
    {
        $this->arguments = [];

        return $this;
    }

    /**
     * Sets the working directory
     *
     * @param string|null $directory The working directory
     *
     * @return ProcessBuilder
     */
    public function directory(?string $directory = null): ProcessBuilder
    {
        $this->directory = $directory;

        return $this;
    }

    /**
     * Sets the input
     *
     * @param resource|string|null $input The input
     *
     * @return ProcessBuilder
     */
    public function input($input): ProcessBuilder
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
     * @param int|float|null $timeout The timeout in seconds
     *
     * @return ProcessBuilder
     *
     * @throws DomainException When the timeout is invalid
     */
    public function timeout($timeout): ProcessBuilder
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
     *
     * @param bool $inheritEnv Whether to inherit environment variables
     *
     * @return ProcessBuilder
     */
    public function inheritEnv(bool $inheritEnv = true): ProcessBuilder
    {
        $this->inheritEnv = $inheritEnv;

        return $this;
    }

    /**
     * Sets an environment variable
     *
     * @param string      $name  The variable name
     * @param string|null $value The variable value
     *
     * @return ProcessBuilder
     */
    public function setEnv(string $name, ?string $value = null): ProcessBuilder
    {
        $this->environment[$name] = $value;

        return $this;
    }

    /**
     * Adds a callback to receive STDOUT
     *
     * @param callable|null $stdout The callback for STDOUT
     *
     * @return ProcessBuilder
     */
    public function stdout(?callable $stdout = null): ProcessBuilder
    {
        $this->stdout = $stdout;

        return $this;
    }

    /**
     * Adds a callback to receive STDERR
     *
     * @param callable|null $stderr The callback for STDERR
     *
     * @return ProcessBuilder
     */
    public function stderr(?callable $stderr = null): ProcessBuilder
    {
        $this->stderr = $stderr;

        return $this;
    }

    /**
     * Disables output fetching
     *
     * @return ProcessBuilder
     */
    public function disableOutput(): ProcessBuilder
    {
        $this->outputDisabled = true;

        return $this;
    }

    /**
     * Enables output fetching
     *
     * @return ProcessBuilder
     */
    public function enableOutput(): ProcessBuilder
    {
        $this->outputDisabled = false;

        return $this;
    }

    /**
     * Creates a Process instance
     *
     * @return Process
     *
     * @throws MethodCallException When arguments are not present
     */
    public function getProcess(): Process
    {
        if (count($this->prefix) === 0 && count($this->arguments) === 0) {
            throw new MethodCallException('You must add arguments before calling getProcess()');
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
            $this->input,
            $this->timeout,
            $this->stdout,
            $this->stderr
        );

        if ($this->outputDisabled) {
            $process->disableOutput();
        }

        return $process;
    }
}
