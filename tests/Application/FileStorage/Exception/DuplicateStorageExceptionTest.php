<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Application\FileStorage\Exception;

use Novuso\Common\Application\FileStorage\Exception\DuplicateStorageException;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\FileStorage\Exception\DuplicateStorageException
 */
class DuplicateStorageExceptionTest extends UnitTestCase
{
    public function test_that_from_key_returns_expected_exception()
    {
        $exception = DuplicateStorageException::fromKey('foobar');

        static::assertSame('foobar', $exception->getKey());
    }
}
