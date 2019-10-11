<?php declare(strict_types=1);

namespace Novuso\Common\Test\Application\Validation;

use Novuso\Common\Application\Validation\Data\ApplicationData;
use Novuso\Common\Application\Validation\Data\ErrorData;
use Novuso\Common\Application\Validation\ValidationResult;
use Novuso\System\Exception\MethodCallException;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Validation\ValidationResult
 */
class ValidationResultTest extends UnitTestCase
{
    public function test_that_passed_returns_expected_instance()
    {
        $input = ['foo' => 'bar'];

        $result = ValidationResult::passed(new ApplicationData($input));

        $this->assertTrue($result->isPassed());
        $this->assertSame('bar', $result->getData()->get('foo'));
    }

    public function test_that_failed_returns_expected_instance()
    {
        $errors = ['foo' => ['Foo is required']];

        $result = ValidationResult::failed(new ErrorData($errors));

        $this->assertTrue($result->isFailed());
        $this->assertSame('Foo is required', $result->getErrors()->get('foo')[0]);
    }

    public function test_that_get_data_throws_exception_when_validation_failed()
    {
        $this->expectException(MethodCallException::class);

        $errors = ['foo' => ['Foo is required']];

        $result = ValidationResult::failed(new ErrorData($errors));

        $result->getData();
    }

    public function test_that_get_errors_throws_exception_when_validation_passed()
    {
        $this->expectException(MethodCallException::class);

        $input = ['foo' => 'bar'];

        $result = ValidationResult::passed(new ApplicationData($input));

        $result->getErrors();
    }
}
