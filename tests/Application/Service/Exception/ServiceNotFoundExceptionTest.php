<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Application\Service\Exception;

use Novuso\Common\Application\Service\Exception\ServiceNotFoundException;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Service\Exception\ServiceNotFoundException
 */
class ServiceNotFoundExceptionTest extends UnitTestCase
{
    public function test_that_from_name_returns_expected_instance()
    {
        $exception = ServiceNotFoundException::fromName('event_dispatcher');

        static::assertSame('Undefined service: event_dispatcher', $exception->getMessage());
    }
}
