<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Application\FileStorage\Exception;

use Novuso\Common\Application\FileStorage\Exception\StorageNotFoundException;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\FileStorage\Exception\StorageNotFoundException
 */
class StorageNotFoundExceptionTest extends UnitTestCase
{
    public function test_that_from_key_returns_expected_exception()
    {
        $exception = StorageNotFoundException::fromKey('foobar');

        static::assertSame('foobar', $exception->getKey());
    }
}
