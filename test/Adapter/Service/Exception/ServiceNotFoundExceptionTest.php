<?php

namespace Novuso\Test\Common\Adapter\Service\Exception;

use Novuso\Common\Adapter\Service\Exception\ServiceNotFoundException;
use Novuso\Test\System\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Adapter\Service\Exception\ServiceNotFoundException
 */
class ServiceNotFoundExceptionTest extends UnitTestCase
{
    public function test_that_from_name_returns_expected_instance()
    {
        $exception = ServiceNotFoundException::fromName('event_dispatcher');
        $this->assertSame('Undefined service: event_dispatcher', $exception->getMessage());
    }
}
