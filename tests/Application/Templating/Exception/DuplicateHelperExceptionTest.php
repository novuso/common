<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Application\Templating\Exception;

use Novuso\Common\Application\Templating\Exception\DuplicateHelperException;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Templating\Exception\DuplicateHelperException
 */
class DuplicateHelperExceptionTest extends UnitTestCase
{
    public function test_that_from_name_returns_expected_instance()
    {
        $exception = DuplicateHelperException::fromName('appHelper');

        static::assertSame('appHelper', $exception->getName());
    }
}
