<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Application\Validation\Rule;

use Novuso\Common\Application\Validation\Rule\IsNaturalNumber;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Validation\Rule\IsNaturalNumber
 */
class IsNaturalNumberTest extends UnitTestCase
{
    public function test_that_is_satisfied_by_returns_true_when_validation_passes()
    {
        $rule = new IsNaturalNumber();

        static::assertTrue($rule->isSatisfiedBy('42'));
    }

    public function test_that_is_satisfied_by_returns_false_when_validation_fails()
    {
        $rule = new IsNaturalNumber();

        static::assertFalse($rule->isSatisfiedBy('-12'));
    }
}
