<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Application\Process;

use Novuso\Common\Application\Process\Process;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Process\Process
 */
class ProcessTest extends UnitTestCase
{
    public function test_that_command_returns_expected_string()
    {
        $process = new Process('ls -la');

        static::assertSame('ls -la', $process->command());
    }

    public function test_that_directory_returns_expected_string()
    {
        $process = new Process('ls -la', '/tmp');

        static::assertSame('/tmp', $process->directory());
    }

    public function test_that_environment_returns_expected_value()
    {
        $process = new Process('ls -la', '/tmp', ['FOO' => 'bar']);
        $env = $process->environment();

        static::assertSame('bar', $env['FOO']);
    }

    public function test_that_input_returns_expected_resource()
    {
        $fp = fopen('php://stdin', 'r');
        $process = new Process('ls -la', '/tmp', null, null, null, null, $fp);

        static::assertTrue(is_resource($process->input()));

        fclose($fp);
    }

    public function test_that_input_returns_expected_string()
    {
        $process = new Process('ls -la', '/tmp', null, null, null, null, 'input');

        static::assertSame('input', $process->input());
    }

    public function test_that_timeout_returns_expected_float()
    {
        $process = new Process('ls -la', '/tmp', null, 3.5);

        static::assertSame(3.5, $process->timeout());
    }

    public function test_that_stdout_returns_expected_callback()
    {
        $stdout = function ($data) {
            echo $data;
        };
        $process = new Process('ls -la', '/tmp', null, null, $stdout);

        static::assertSame($stdout, $process->stdout());
    }

    public function test_that_stderr_returns_expected_callback()
    {
        $stderr = function ($data) {
            echo $data;
        };
        $process = new Process('ls -la', '/tmp', null, null, null, $stderr);

        static::assertSame($stderr, $process->stderr());
    }

    public function test_that_is_output_disabled_returns_false_when_enabled()
    {
        $process = new Process('ls -la');
        $process->enableOutput();

        static::assertFalse($process->isOutputDisabled());
    }

    public function test_that_is_output_disabled_returns_true_when_disabled()
    {
        $process = new Process('ls -la');
        $process->disableOutput();

        static::assertTrue($process->isOutputDisabled());
    }
}
