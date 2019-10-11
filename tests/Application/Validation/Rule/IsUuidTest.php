<?php declare(strict_types=1);

namespace Novuso\Common\Test\Application\Validation\Rule;

use Novuso\Common\Application\Validation\Rule\IsUuid;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Validation\Rule\IsUuid
 */
class IsUuidTest extends UnitTestCase
{
    public function test_that_is_satisfied_by_returns_true_when_validation_passes()
    {
        $rule = new IsUuid();

        $this->assertTrue($rule->isSatisfiedBy('6ba7b811-9dad-11d1-80b4-00c04fd430c8'));
    }

    public function test_that_is_satisfied_by_returns_false_when_validation_fails()
    {
        $rule = new IsUuid();

        $this->assertFalse($rule->isSatisfiedBy('6ba7b811-9dad-11d1-80b4-00c04fd4'));
    }
}
