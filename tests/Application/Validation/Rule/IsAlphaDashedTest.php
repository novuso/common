<?php declare(strict_types=1);

namespace Novuso\Common\Test\Application\Validation\Rule;

use Novuso\Common\Application\Validation\Rule\IsAlphaDashed;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Validation\Rule\IsAlphaDashed
 */
class IsAlphaDashedTest extends UnitTestCase
{
    public function test_that_is_satisfied_by_returns_true_when_validation_passes()
    {
        $rule = new IsAlphaDashed();

        $this->assertTrue($rule->isSatisfiedBy('abcdefghijkl-mnopqrstu_vwxyz'));
    }

    public function test_that_is_satisfied_by_returns_false_when_validation_fails()
    {
        $rule = new IsAlphaDashed();

        $this->assertFalse($rule->isSatisfiedBy('djewh4i88f'));
    }
}
