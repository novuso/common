<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Application\Validation\Rule;

use Novuso\Common\Application\Validation\Rule\StringContains;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Validation\Rule\StringContains
 */
class StringContainsTest extends UnitTestCase
{
    public function test_that_is_satisfied_by_returns_true_when_validation_passes()
    {
        $rule = new StringContains('foo');

        static::assertTrue($rule->isSatisfiedBy('foobar'));
    }

    public function test_that_is_satisfied_by_returns_false_when_validation_fails()
    {
        $rule = new StringContains('foo');

        static::assertFalse($rule->isSatisfiedBy('hello'));
    }
}
