<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Application\Security;

use Mockery\MockInterface;
use Novuso\Common\Application\Security\TokenDecoder;
use Novuso\Common\Application\Security\TokenEncoder;
use Novuso\Common\Application\Security\TokenService;
use Novuso\Common\Domain\Value\DateTime\DateTime;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Security\TokenService
 */
class TokenServiceTest extends UnitTestCase
{
    /** @var TokenService */
    protected $tokenService;
    /** @var TokenEncoder|MockInterface */
    protected $mockTokenEncoder;
    /** @var TokenDecoder|MockInterface */
    protected $mockTokenDecoder;

    protected function setUp(): void
    {
        $this->mockTokenEncoder = $this->mock(TokenEncoder::class);
        $this->mockTokenDecoder = $this->mock(TokenDecoder::class);
        $this->tokenService = new TokenService(
            $this->mockTokenEncoder,
            $this->mockTokenDecoder
        );
    }

    public function test_that_decode_delegates_to_service()
    {
        $token = '{foo:bar}';

        $this->mockTokenDecoder
            ->shouldReceive('decode')
            ->once()
            ->with($token)
            ->andReturn(['foo' => 'bar']);

        $this->tokenService->decode($token);
    }

    public function test_that_encode_delegates_to_service()
    {
        $claims = ['foo' => 'bar'];
        $expiration = DateTime::now()->modify('+1 day');

        $this->mockTokenEncoder
            ->shouldReceive('encode')
            ->once()
            ->with($claims, $expiration)
            ->andReturn('{foo:bar}');

        $this->tokenService->encode($claims, $expiration);
    }
}
