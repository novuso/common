<?php declare(strict_types=1);

namespace Novuso\Common\Test\Application\Validation\Rule;

use Novuso\Common\Application\Validation\Rule\NumberMax;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Validation\Rule\NumberMax
 */
class NumberMaxTest extends UnitTestCase
{
    public function test_that_is_satisfied_by_returns_true_when_validation_passes()
    {
        $rule = new NumberMax(3);

        $this->assertTrue($rule->isSatisfiedBy(2));
    }

    public function test_that_is_satisfied_by_returns_false_when_validation_fails()
    {
        $rule = new NumberMax(3);

        $this->assertFalse($rule->isSatisfiedBy(5));
    }
}
