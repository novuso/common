<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Application\HttpClient\Exception;

use Mockery\MockInterface;
use Novuso\Common\Application\HttpClient\Exception\RequestException;
use Novuso\System\Test\TestCase\UnitTestCase;
use Psr\Http\Message\RequestInterface;

/**
 * @covers \Novuso\Common\Application\HttpClient\Exception\RequestException
 */
class RequestExceptionTest extends UnitTestCase
{
    public function test_that_get_request_returns_expected_instance()
    {
        /** @var RequestInterface|MockInterface $request */
        $request = $this->mock(RequestInterface::class);
        $exception = new RequestException('Something went wrong', $request);
        static::assertSame($request, $exception->getRequest());
    }
}
