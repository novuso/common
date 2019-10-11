<?php declare(strict_types=1);

namespace Novuso\Common\Test\Application\Validation\Rule;

use Novuso\Common\Application\Validation\Rule\IsWholeNumber;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Validation\Rule\IsWholeNumber
 */
class IsWholeNumberTest extends UnitTestCase
{
    public function test_that_is_satisfied_by_returns_true_when_validation_passes()
    {
        $rule = new IsWholeNumber();

        $this->assertTrue($rule->isSatisfiedBy('0'));
    }

    public function test_that_is_satisfied_by_returns_false_when_validation_fails()
    {
        $rule = new IsWholeNumber();

        $this->assertFalse($rule->isSatisfiedBy('-1'));
    }
}
