<?php declare(strict_types=1);

namespace Novuso\Common\Test\Application\Validation\Rule;

use Novuso\Common\Application\Validation\Rule\IsFalse;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Validation\Rule\IsFalse
 */
class IsFalseTest extends UnitTestCase
{
    public function test_that_is_satisfied_by_returns_true_when_validation_passes()
    {
        $rule = new IsFalse();

        $this->assertTrue($rule->isSatisfiedBy(false));
    }

    public function test_that_is_satisfied_by_returns_false_when_validation_fails()
    {
        $rule = new IsFalse();

        $this->assertFalse($rule->isSatisfiedBy(true));
    }
}
