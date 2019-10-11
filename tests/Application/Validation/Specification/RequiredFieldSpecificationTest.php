<?php declare(strict_types=1);

namespace Novuso\Common\Test\Application\Validation\Specification;

use Mockery\MockInterface;
use Novuso\Common\Application\Validation\Specification\RequiredFieldSpecification;
use Novuso\Common\Application\Validation\ValidationContext;
use Novuso\System\Exception\KeyException;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Validation\Specification\RequiredFieldSpecification
 */
class RequiredFieldSpecificationTest extends UnitTestCase
{
    public function test_that_is_satisfied_by_returns_true_when_key_present()
    {
        /** @var ValidationContext|MockInterface $context */
        $context = $this->mock(ValidationContext::class);

        $context
            ->shouldReceive('get')
            ->once()
            ->with('foo')
            ->andReturn('bar');

        $spec = new RequiredFieldSpecification('foo');

        $this->assertTrue($spec->isSatisfiedBy($context));
    }

    public function test_that_is_satisfied_by_returns_false_when_key_missing()
    {
        /** @var ValidationContext|MockInterface $context */
        $context = $this->mock(ValidationContext::class);

        $context
            ->shouldReceive('get')
            ->once()
            ->with('foo')
            ->andThrow(new KeyException());

        $spec = new RequiredFieldSpecification('foo');

        $this->assertFalse($spec->isSatisfiedBy($context));
    }
}
