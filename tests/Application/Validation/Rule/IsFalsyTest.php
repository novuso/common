<?php declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Rule;

use Novuso\Common\Application\Validation\Rule\IsFalsy;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Validation\Rule\IsFalsy
 */
class IsFalsyTest extends UnitTestCase
{
    public function test_that_is_satisfied_by_returns_true_when_validation_passes()
    {
        $rule = new IsFalsy();

        $this->assertTrue($rule->isSatisfiedBy(0));
    }

    public function test_that_is_satisfied_by_returns_false_when_validation_fails()
    {
        $rule = new IsFalsy();

        $this->assertFalse($rule->isSatisfiedBy(1));
    }
}
