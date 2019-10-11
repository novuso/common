<?php declare(strict_types=1);

namespace Novuso\Common\Test\Application\Validation\Rule;

use Novuso\Common\Application\Validation\Rule\IsUrn;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Validation\Rule\IsUrn
 */
class IsUrnTest extends UnitTestCase
{
    public function test_that_is_satisfied_by_returns_true_when_validation_passes()
    {
        $rule = new IsUrn();

        $this->assertTrue($rule->isSatisfiedBy('urn:isbn:0451450523'));
    }

    public function test_that_is_satisfied_by_returns_false_when_validation_fails()
    {
        $rule = new IsUrn();

        $this->assertFalse($rule->isSatisfiedBy('urn:urn:something'));
    }
}
