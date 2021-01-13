<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Application\Validation\Rule;

use Novuso\Common\Application\Validation\Rule\IsAlnum;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Validation\Rule\IsAlnum
 */
class IsAlnumTest extends UnitTestCase
{
    public function test_that_is_satisfied_by_returns_true_when_validation_passes()
    {
        $rule = new IsAlnum();

        static::assertTrue($rule->isSatisfiedBy('abcdefghijklmnopqrstuvwxyz1234567890'));
    }

    public function test_that_is_satisfied_by_returns_false_when_validation_fails()
    {
        $rule = new IsAlnum();

        static::assertFalse($rule->isSatisfiedBy('asdj-d34'));
    }
}
