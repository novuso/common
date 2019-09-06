<?php declare(strict_types=1);

namespace Novuso\Common\Test\Application\Container\Exception;

use Novuso\Common\Application\Container\Exception\ServiceNotFoundException;
use Novuso\Common\Test\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Container\Exception\ServiceNotFoundException
 */
class ServiceNotFoundExceptionTest extends UnitTestCase
{
    public function test_that_from_name_returns_expected_instance()
    {
        $exception = ServiceNotFoundException::fromName('event_dispatcher');
        $this->assertSame('Undefined service: event_dispatcher', $exception->getMessage());
    }
}
