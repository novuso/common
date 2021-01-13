<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Application\Validation\Rule;

use Novuso\Common\Application\Validation\Rule\KeyNotEmpty;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Validation\Rule\KeyNotEmpty
 */
class KeyNotEmptyTest extends UnitTestCase
{
    public function test_that_is_satisfied_by_returns_true_when_validation_passes()
    {
        $rule = new KeyNotEmpty('foo');

        static::assertTrue($rule->isSatisfiedBy(['foo' => 'bar']));
    }

    public function test_that_is_satisfied_by_returns_false_when_validation_fails()
    {
        $rule = new KeyNotEmpty('foo');

        static::assertFalse($rule->isSatisfiedBy(['foo' => '']));
    }
}
