<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Application\Validation\Rule;

use Novuso\Common\Application\Validation\Rule\NumberMin;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Validation\Rule\NumberMin
 */
class NumberMinTest extends UnitTestCase
{
    public function test_that_is_satisfied_by_returns_true_when_validation_passes()
    {
        $rule = new NumberMin(3);

        static::assertTrue($rule->isSatisfiedBy(4));
    }

    public function test_that_is_satisfied_by_returns_false_when_validation_fails()
    {
        $rule = new NumberMin(3);

        static::assertFalse($rule->isSatisfiedBy(1));
    }
}
