<?php declare(strict_types=1);

namespace Novuso\Common\Test\Application\Container\Exception;

use Novuso\Common\Application\Container\Exception\ServiceContainerException;
use Novuso\Common\Test\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Container\Exception\ServiceContainerException
 */
class ServiceContainerExceptionTest extends UnitTestCase
{
    public function test_that_get_service_returns_null_when_service_is_not_set()
    {
        $exception = new ServiceContainerException('Something went wrong');
        $this->assertNull($exception->getService());
    }

    public function test_that_get_service_returns_expected_service()
    {
        $exception = new ServiceContainerException('Something went wrong', 'event_dispatcher');
        $this->assertSame('event_dispatcher', $exception->getService());
    }
}
