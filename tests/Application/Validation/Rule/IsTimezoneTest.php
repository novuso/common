<?php declare(strict_types=1);

namespace Novuso\Common\Test\Application\Validation\Rule;

use DateTimeZone;
use Novuso\Common\Application\Validation\Rule\IsTimezone;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Validation\Rule\IsTimezone
 */
class IsTimezoneTest extends UnitTestCase
{
    public function test_that_is_satisfied_by_returns_true_when_validation_passes()
    {
        $rule = new IsTimezone();

        $this->assertTrue($rule->isSatisfiedBy('America/Chicago'));
    }

    public function test_that_is_satisfied_by_returns_true_when_validation_passes_date_time_zone()
    {
        $rule = new IsTimezone();

        $this->assertTrue($rule->isSatisfiedBy(new DateTimeZone('America/Chicago')));
    }

    public function test_that_is_satisfied_by_returns_false_when_validation_fails()
    {
        $rule = new IsTimezone();

        $this->assertFalse($rule->isSatisfiedBy('UniversalTime'));
    }
}
