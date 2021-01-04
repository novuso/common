<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Application\Validation\Exception;

use Novuso\Common\Application\Validation\Exception\ValidationException;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Validation\Exception\ValidationException
 */
class ValidationExceptionTest extends UnitTestCase
{
    public function test_that_from_errors_returns_expected_instance()
    {
        $errors = [
            'foo' => [
                'Foo cannot be blank',
                'Foo must be at least 3 characters in length'
            ]
        ];

        $exception = ValidationException::fromErrors($errors);

        static::assertSame($errors, $exception->getErrors());
    }
}
