<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Application\Validation\Rule;

use Novuso\Common\Application\Validation\Rule\IsAlpha;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Validation\Rule\IsAlpha
 */
class IsAlphaTest extends UnitTestCase
{
    public function test_that_is_satisfied_by_returns_true_when_validation_passes()
    {
        $rule = new IsAlpha();

        static::assertTrue($rule->isSatisfiedBy('abcdefghijklmnopqrstuvwxyz'));
    }

    public function test_that_is_satisfied_by_returns_false_when_validation_fails()
    {
        $rule = new IsAlpha();

        static::assertFalse($rule->isSatisfiedBy('12a'));
    }
}
