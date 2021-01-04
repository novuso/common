<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Application\Validation\Rule;

use Novuso\Common\Application\Validation\Rule\LengthExact;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Validation\Rule\LengthExact
 */
class LengthExactTest extends UnitTestCase
{
    public function test_that_is_satisfied_by_returns_true_when_validation_passes()
    {
        $rule = new LengthExact(3);

        static::assertTrue($rule->isSatisfiedBy('foo'));
    }

    public function test_that_is_satisfied_by_returns_false_when_validation_fails()
    {
        $rule = new LengthExact(3);

        static::assertFalse($rule->isSatisfiedBy('hello'));
    }
}
