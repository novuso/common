<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Application\Validation;

use Mockery\MockInterface;
use Novuso\Common\Application\Validation\BasicValidator;
use Novuso\Common\Application\Validation\ValidationContext;
use Novuso\Common\Domain\Specification\Specification;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Validation\BasicValidator
 */
class BasicValidatorTest extends UnitTestCase
{
    /** @var BasicValidator */
    protected $validator;
    /** @var Specification|MockInterface */
    protected $mockSpecification;
    /** @var ValidationContext|MockInterface */
    protected $mockContext;
    /** @var string */
    protected $fieldName;
    /** @var string */
    protected $errorMessage;

    protected function setUp(): void
    {
        $this->mockContext = $this->mock(ValidationContext::class);
        $this->mockSpecification = $this->mock(Specification::class);
        $this->fieldName = 'foo';
        $this->errorMessage = 'Foo is required';
        $this->validator = new BasicValidator($this->mockSpecification, $this->fieldName, $this->errorMessage);
    }

    public function test_that_validate_returns_true_when_validation_passes()
    {
        $this->mockSpecification
            ->shouldReceive('isSatisfiedBy')
            ->once()
            ->with($this->mockContext)
            ->andReturn(true);

        static::assertTrue($this->validator->validate($this->mockContext));
    }

    public function test_that_error_is_added_to_context_when_validation_fails()
    {
        $this->mockContext
            ->shouldReceive('addError')
            ->once()
            ->with($this->fieldName, $this->errorMessage)
            ->andReturnNull();

        $this->mockSpecification
            ->shouldReceive('isSatisfiedBy')
            ->once()
            ->with($this->mockContext)
            ->andReturn(false);

        static::assertFalse($this->validator->validate($this->mockContext));
    }
}
