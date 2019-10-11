<?php declare(strict_types=1);

namespace Novuso\Common\Test\Application\Validation\Rule;

use Novuso\Common\Application\Validation\Rule\IsDateTime;
use Novuso\Common\Domain\Value\DateTime\DateTime;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Validation\Rule\IsDateTime
 */
class IsDateTimeTest extends UnitTestCase
{
    public function test_that_is_satisfied_by_returns_true_when_validation_passes_date_time_string()
    {
        $rule = new IsDateTime(DateTime::STRING_FORMAT);

        $this->assertTrue($rule->isSatisfiedBy('2016-01-20T13:32:30.000003[UTC]'));
    }

    public function test_that_is_satisfied_by_returns_false_when_validation_fails_date_time_string()
    {
        $rule = new IsDateTime(DateTime::STRING_FORMAT);

        $this->assertFalse($rule->isSatisfiedBy('2016-01-20T13:32:30[UTC]'));
    }

    public function test_that_is_satisfied_by_returns_true_when_validation_passes_format_string()
    {
        $rule = new IsDateTime('Y-m-d H:i:s');

        $this->assertTrue($rule->isSatisfiedBy('2016-01-20 13:32:30'));
    }

    public function test_that_is_satisfied_by_returns_false_when_validation_fails_error()
    {
        $rule = new IsDateTime('Y-m-d H:i:s');

        $this->assertFalse($rule->isSatisfiedBy('Some string that is not a date/time'));
    }

    public function test_that_is_satisfied_by_returns_false_when_validation_fails_invalid()
    {
        $rule = new IsDateTime('Y-m-d H:i:s');

        $this->assertFalse($rule->isSatisfiedBy('2016-02-30 13:32:30'));
    }
}
