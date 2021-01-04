<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Application\Validation\Rule;

use Novuso\Common\Application\Validation\Rule\NumberRange;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Validation\Rule\NumberRange
 */
class NumberRangeTest extends UnitTestCase
{
    public function test_that_is_satisfied_by_returns_true_when_validation_passes()
    {
        $rule = new NumberRange(3, 10);

        static::assertTrue($rule->isSatisfiedBy(5));
    }

    public function test_that_is_satisfied_by_returns_false_when_validation_fails()
    {
        $rule = new NumberRange(3, 10);

        static::assertFalse($rule->isSatisfiedBy(15));
    }
}
