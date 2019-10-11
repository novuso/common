<?php declare(strict_types=1);

namespace Novuso\Common\Test\Application\Validation\Rule;

use Novuso\Common\Application\Validation\Rule\NumberExact;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Validation\Rule\NumberExact
 */
class NumberExactTest extends UnitTestCase
{
    public function test_that_is_satisfied_by_returns_true_when_validation_passes()
    {
        $rule = new NumberExact(3.0);

        $this->assertTrue($rule->isSatisfiedBy(3));
    }

    public function test_that_is_satisfied_by_returns_false_when_validation_fails()
    {
        $rule = new NumberExact(3.14);

        $this->assertFalse($rule->isSatisfiedBy(3));
    }
}
