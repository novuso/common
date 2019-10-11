<?php declare(strict_types=1);

namespace Novuso\Common\Test\Application\Validation\Rule;

use Novuso\Common\Application\Validation\Rule\LengthRange;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Validation\Rule\LengthRange
 */
class LengthRangeTest extends UnitTestCase
{
    public function test_that_is_satisfied_by_returns_true_when_validation_passes()
    {
        $rule = new LengthRange(3, 10);

        $this->assertTrue($rule->isSatisfiedBy('foo'));
    }

    public function test_that_is_satisfied_by_returns_false_when_validation_fails()
    {
        $rule = new LengthRange(3, 10);

        $this->assertFalse($rule->isSatisfiedBy('ya'));
    }
}
