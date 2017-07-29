<?php

namespace Novuso\Test\Common\Application\HttpClient\Exception;

use Novuso\Common\Application\HttpClient\Exception\RequestException;
use Novuso\Test\System\TestCase\UnitTestCase;
use Psr\Http\Message\RequestInterface;

/**
 * @covers \Novuso\Common\Application\HttpClient\Exception\RequestException
 */
class RequestExceptionTest extends UnitTestCase
{
    public function test_that_get_request_returns_expected_instance()
    {
        /** @var RequestInterface $request */
        $request = $this->mock(RequestInterface::class);
        $exception = new RequestException('Something went wrong', $request);
        $this->assertSame($request, $exception->getRequest());
    }
}
