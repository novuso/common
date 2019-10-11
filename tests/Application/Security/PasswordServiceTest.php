<?php declare(strict_types=1);

namespace Novuso\Common\Test\Application\Security;

use Mockery\MockInterface;
use Novuso\Common\Application\Security\PasswordHasher;
use Novuso\Common\Application\Security\PasswordService;
use Novuso\Common\Application\Security\PasswordValidator;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Security\PasswordService
 */
class PasswordServiceTest extends UnitTestCase
{
    /** @var PasswordService */
    protected $passwordService;
    /** @var PasswordHasher|MockInterface */
    protected $hasher;
    /** @var PasswordValidator|MockInterface */
    protected $validator;

    protected function setUp(): void
    {
        $this->hasher = $this->mock(PasswordHasher::class);
        $this->validator = $this->mock(PasswordValidator::class);
        $this->passwordService = new PasswordService($this->hasher, $this->validator);
    }

    public function test_that_hash_delegates_to_services()
    {
        $password = 'hello_dolly';
        $hash = '$2y$10$CyLjVeFNYtEZLi.iVHI3pubjO.iBPIoOODTkl1fPRB9vmLhWsLr4G';

        $this->hasher
            ->shouldReceive('hash')
            ->once()
            ->with($password)
            ->andReturn($hash);

        $this->passwordService->hash($password);
    }

    public function test_that_validate_delegates_to_services()
    {
        $password = 'hello_dolly';
        $hash = '$2y$10$CyLjVeFNYtEZLi.iVHI3pubjO.iBPIoOODTkl1fPRB9vmLhWsLr4G';

        $this->validator
            ->shouldReceive('validate')
            ->once()
            ->with($password, $hash)
            ->andReturnTrue();

        $this->passwordService->validate($password, $hash);
    }

    public function test_that_needs_rehash_delegates_to_services()
    {
        $hash = '$2y$10$CyLjVeFNYtEZLi.iVHI3pubjO.iBPIoOODTkl1fPRB9vmLhWsLr4G';

        $this->validator
            ->shouldReceive('needsRehash')
            ->once()
            ->with($hash)
            ->andReturnFalse();

        $this->passwordService->needsRehash($hash);
    }
}
