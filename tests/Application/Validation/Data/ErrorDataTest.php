<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Application\Validation\Data;

use Novuso\Common\Application\Validation\Data\ErrorData;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Validation\Data\ErrorData
 */
class ErrorDataTest extends UnitTestCase
{
    public function test_that_get_returns_empty_array_for_undefined_key()
    {
        $data = new ErrorData([]);

        static::assertSame([], $data->get('foo'));
    }

    public function test_that_get_returns_expected_value()
    {
        $errors = [
            'Foo is required',
            'Foo must be at least 3 characters in length'
        ];

        $data = new ErrorData(['foo' => $errors]);

        static::assertSame($errors, $data->get('foo'));
    }

    public function test_that_has_returns_true_when_key_is_defined()
    {
        $errors = [
            'Foo is required',
            'Foo must be at least 3 characters in length'
        ];

        $data = new ErrorData(['foo' => $errors]);

        static::assertTrue($data->has('foo'));
    }

    public function test_that_has_returns_false_when_key_is_not_defined()
    {
        $data = new ErrorData([]);

        static::assertFalse($data->has('foo'));
    }

    public function test_that_names_returns_list_of_defined_keys()
    {
        $data = new ErrorData([
            'foo' => [
                'Foo is required',
                'Foo must be at least 3 characters in length'
            ],
            'bar' => [
                'Bar is required',
                'Bar must be equal to Foo'
            ]
        ]);

        static::assertSame(['foo', 'bar'], $data->names());
    }

    public function test_that_is_empty_returns_true_for_empty_collection()
    {
        $data = new ErrorData([]);

        static::assertTrue($data->isEmpty());
    }

    public function test_that_count_returns_expected_count()
    {
        $data = new ErrorData([
            'foo' => [
                'Foo is required',
                'Foo must be at least 3 characters in length'
            ],
            'bar' => [
                'Bar is required',
                'Bar must be equal to Foo'
            ]
        ]);

        static::assertCount(2, $data);
    }

    public function test_that_it_is_iterable()
    {
        $data = new ErrorData([
            'foo' => [
                'Foo is required',
                'Foo must be at least 3 characters in length'
            ],
            'bar' => [
                'Bar is required',
                'Bar must be equal to Foo'
            ]
        ]);

        $count = 0;
        foreach ($data as $name => $value) {
            $count++;
        }

        static::assertSame(2, $count);
    }

    public function test_that_to_array_returns_expected_value()
    {
        $errors = [
            'foo' => [
                'Foo is required',
                'Foo must be at least 3 characters in length'
            ],
            'bar' => [
                'Bar is required',
                'Bar must be equal to Foo'
            ]
        ];

        $data = new ErrorData($errors);

        static::assertSame($errors, $data->toArray());
    }
}
