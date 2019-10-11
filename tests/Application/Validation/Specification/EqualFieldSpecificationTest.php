<?php declare(strict_types=1);

namespace Novuso\Common\Test\Application\Validation\Specification;

use Mockery\MockInterface;
use Novuso\Common\Application\Validation\Specification\EqualFieldsSpecification;
use Novuso\Common\Application\Validation\ValidationContext;
use Novuso\System\Exception\KeyException;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Validation\Specification\EqualFieldsSpecification
 */
class EqualFieldSpecificationTest extends UnitTestCase
{
    public function test_that_is_satisfied_by_returns_true_when_validation_passes()
    {
        /** @var ValidationContext|MockInterface $context */
        $context = $this->mock(ValidationContext::class);

        $context
            ->shouldReceive('get')
            ->once()
            ->with('foo')
            ->andReturn('bar');

        $context
            ->shouldReceive('get')
            ->once()
            ->with('baz')
            ->andReturn('bar');

        $spec = new EqualFieldsSpecification('foo', 'baz');

        $this->assertTrue($spec->isSatisfiedBy($context));
    }

    public function test_that_is_satisfied_by_returns_false_when_validation_fails()
    {
        /** @var ValidationContext|MockInterface $context */
        $context = $this->mock(ValidationContext::class);

        $context
            ->shouldReceive('get')
            ->once()
            ->with('foo')
            ->andReturn('bar');

        $context
            ->shouldReceive('get')
            ->once()
            ->with('baz')
            ->andReturn('buz');

        $spec = new EqualFieldsSpecification('foo', 'baz');

        $this->assertFalse($spec->isSatisfiedBy($context));
    }

    public function test_that_is_satisfied_by_returns_true_when_field_name_is_not_defined()
    {
        /** @var ValidationContext|MockInterface $context */
        $context = $this->mock(ValidationContext::class);

        $context
            ->shouldReceive('get')
            ->once()
            ->with('foo')
            ->andReturn('bar');

        $context
            ->shouldReceive('get')
            ->once()
            ->with('baz')
            ->andThrow(new KeyException());

        $spec = new EqualFieldsSpecification('foo', 'baz');

        $this->assertTrue($spec->isSatisfiedBy($context));
    }
}
