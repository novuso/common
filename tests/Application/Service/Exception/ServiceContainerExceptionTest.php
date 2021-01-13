<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Application\Service\Exception;

use Novuso\Common\Application\Service\Exception\ServiceContainerException;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Service\Exception\ServiceContainerException
 */
class ServiceContainerExceptionTest extends UnitTestCase
{
    public function test_that_get_service_returns_null_when_service_is_not_set()
    {
        $exception = new ServiceContainerException('Something went wrong');

        static::assertNull($exception->getService());
    }

    public function test_that_get_service_returns_expected_service()
    {
        $exception = new ServiceContainerException('Something went wrong', 'event_dispatcher');

        static::assertSame('event_dispatcher', $exception->getService());
    }
}
