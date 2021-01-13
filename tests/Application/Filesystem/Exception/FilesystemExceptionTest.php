<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Application\Filesystem\Exception;

use Novuso\Common\Application\Filesystem\Exception\FilesystemException;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Filesystem\Exception\FilesystemException
 */
class FilesystemExceptionTest extends UnitTestCase
{
    public function test_that_get_path_returns_null_when_path_is_not_set()
    {
        $exception = new FilesystemException('Something went wrong');

        static::assertNull($exception->getPath());
    }

    public function test_that_get_path_returns_expected_path()
    {
        $exception = new FilesystemException('Something went wrong', '/tmp');

        static::assertSame('/tmp', $exception->getPath());
    }
}
