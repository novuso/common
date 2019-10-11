<?php declare(strict_types=1);

namespace Novuso\Common\Test\Application\Validation\Rule;

use Novuso\Common\Application\Validation\Rule\IsIpV6Address;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Validation\Rule\IsIpV6Address
 */
class IsIpV6AddressTest extends UnitTestCase
{
    public function test_that_is_satisfied_by_returns_true_when_validation_passes()
    {
        $rule = new IsIpV6Address();

        $this->assertTrue($rule->isSatisfiedBy('2001:0db8:0000:0042:0000:8a2e:0370:7334'));
    }

    public function test_that_is_satisfied_by_returns_false_when_validation_fails()
    {
        $rule = new IsIpV6Address();

        $this->assertFalse($rule->isSatisfiedBy('127.0.0.0.1'));
    }
}
