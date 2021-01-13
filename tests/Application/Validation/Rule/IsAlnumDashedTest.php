<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Application\Validation\Rule;

use Novuso\Common\Application\Validation\Rule\IsAlnumDashed;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Validation\Rule\IsAlnumDashed
 */
class IsAlnumDashedTest extends UnitTestCase
{
    public function test_that_is_satisfied_by_returns_true_when_validation_passes()
    {
        $rule = new IsAlnumDashed();

        static::assertTrue($rule->isSatisfiedBy('abcdefghijkl_mnopqrstuvwxyz-1234567890'));
    }

    public function test_that_is_satisfied_by_returns_false_when_validation_fails()
    {
        $rule = new IsAlnumDashed();

        static::assertFalse($rule->isSatisfiedBy('$'));
    }
}
