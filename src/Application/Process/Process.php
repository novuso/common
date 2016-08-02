<?php declare(strict_types=1);

namespace Novuso\Common\Application\Process;

/**
 * Process represents a command sub-process
 *
 * @copyright Copyright (c) 2016, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class Process
{
    /**
     * Command
     *
     * @var string
     */
    protected $command;

    /**
     * Working directory
     *
     * @var string|null
     */
    protected $directory;

    /**
     * Environment variables
     *
     * @var array|null
     */
    protected $environment;

    /**
     * Input
     *
     * @var resource|string|null
     */
    protected $input;

    /**
     * Timeout
     *
     * @var float|null
     */
    protected $timeout;

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
     * Constructs Process
     *
     * @param string               $command     The command
     * @param string|null          $directory   The working directory
     * @param array|null           $environment The environment variables
     * @param resource|string|null $input       The input
     * @param float|null           $timeout     The timeout
     * @param callable|null        $stdout      The callback for STDOUT
     * @param callable|null        $stderr      The callback for STDERR
     */
    public function __construct(
        string $command,
        string $directory = null,
        array $environment = null,
        $input = null,
        float $timeout = null,
        callable $stdout = null,
        callable $stderr = null
    ) {
        $this->command = $command;
        $this->directory = $directory;
        $this->environment = $environment;
        $this->timeout = $timeout;
        $this->stdout = $stdout;
        $this->stderr = $stderr;
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
     *
     * @return string
     */
    public function command(): string
    {
        return $this->command;
    }

    /**
     * Retrieves the directory
     *
     * @return string|null
     */
    public function directory()
    {
        return $this->directory;
    }

    /**
     * Retrieves the environment
     *
     * @return array|null
     */
    public function environment()
    {
        return $this->environment;
    }

    /**
     * Retrieves the input
     *
     * @return resource|string|null
     */
    public function input()
    {
        return $this->input;
    }

    /**
     * Retrieves the timeout
     *
     * @return float|null
     */
    public function timeout()
    {
        return $this->timeout;
    }

    /**
     * Retrieves STDOUT callback
     *
     * @return callable|null
     */
    public function stdout()
    {
        return $this->stdout;
    }

    /**
     * Retrieves STDERR callback
     *
     * @return callable|null
     */
    public function stderr()
    {
        return $this->stderr;
    }

    /**
     * Disables output fetching
     *
     * @return Process
     */
    public function disableOutput(): Process
    {
        $this->outputDisabled = true;

        return $this;
    }

    /**
     * Enables output fetching
     *
     * @return Process
     */
    public function enableOutput(): Process
    {
        $this->outputDisabled = false;

        return $this;
    }

    /**
     * Checks if output is disabled
     *
     * @return bool
     */
    public function isOutputDisabled(): bool
    {
        return $this->outputDisabled;
    }
}
