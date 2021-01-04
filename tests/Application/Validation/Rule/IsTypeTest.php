<?php declare(strict_types=1);

namespace Novuso\Common\Test\Application\Validation\Rule;

use Novuso\Common\Application\Validation\Rule\IsType;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Validation\Rule\IsType
 */
class IsTypeTest extends UnitTestCase
{
    public function test_that_is_satisfied_by_returns_true_when_validation_passes()
    {
        $rule = new IsType('string');

        $this->assertTrue($rule->isSatisfiedBy('foo'));
    }

    public function test_that_is_satisfied_by_returns_true_when_validation_passes_nullable()
    {
        $rule = new IsType('?string');

        $this->assertTrue($rule->isSatisfiedBy('foo'));
    }

    public function test_that_is_satisfied_by_returns_true_when_validation_passes_nullable_null()
    {
        $rule = new IsType('?string');

        $this->assertTrue($rule->isSatisfiedBy(null));
    }

    public function test_that_is_satisfied_by_returns_false_when_validation_fails()
    {
        $rule = new IsType('string');

        $this->assertFalse($rule->isSatisfiedBy(null));
    }
}
