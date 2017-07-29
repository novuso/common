<?php

namespace Novuso\Test\Common\Application\Filesystem\Exception;

use Novuso\Common\Application\Filesystem\Exception\FileNotFoundException;
use Novuso\Test\System\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Filesystem\Exception\FileNotFoundException
 */
class FileNotFoundExceptionTest extends UnitTestCase
{
    public function test_that_from_path_returns_expected_instance()
    {
        $exception = FileNotFoundException::fromPath('/tmp/file.txt');
        $this->assertSame('File not found: /tmp/file.txt', $exception->getMessage());
    }
}
