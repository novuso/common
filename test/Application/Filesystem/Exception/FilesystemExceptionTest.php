<?php

namespace Novuso\Test\Common\Application\Filesystem\Exception;

use Novuso\Common\Application\Filesystem\Exception\FilesystemException;
use Novuso\Test\System\TestCase\UnitTestCase;

/**
 * @covers Novuso\Common\Application\Filesystem\Exception\FilesystemException
 */
class FilesystemExceptionTest extends UnitTestCase
{
    public function test_that_get_path_returns_null_when_path_is_not_set()
    {
        $exception = new FilesystemException('Something went wrong');
        $this->assertNull($exception->getPath());
    }

    public function test_that_get_path_returns_expected_path()
    {
        $exception = new FilesystemException('Something went wrong', '/tmp');
        $this->assertSame('/tmp', $exception->getPath());
    }
}
