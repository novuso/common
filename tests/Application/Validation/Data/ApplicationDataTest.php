<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Application\Validation\Data;

use Novuso\Common\Application\Validation\Data\ApplicationData;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Validation\Data\ApplicationData
 */
class ApplicationDataTest extends UnitTestCase
{
    public function test_that_get_returns_expected_value()
    {
        $data = new ApplicationData(['foo' => 'bar']);

        static::assertSame('bar', $data->get('foo'));
    }

    public function test_that_get_returns_default_when_key_is_not_defined()
    {
        $data = new ApplicationData([]);

        static::assertSame('default', $data->get('foo', 'default'));
    }

    public function test_that_has_returns_true_when_key_is_defined()
    {
        $data = new ApplicationData(['foo' => 'bar']);

        static::assertTrue($data->has('foo'));
    }

    public function test_that_has_returns_false_when_key_is_not_defined()
    {
        $data = new ApplicationData([]);

        static::assertFalse($data->has('foo'));
    }

    public function test_that_names_returns_list_of_defined_keys()
    {
        $data = new ApplicationData(['foo' => 'bar', 'baz' => 'buz']);

        static::assertSame(['foo', 'baz'], $data->names());
    }

    public function test_that_is_empty_returns_true_for_empty_collection()
    {
        $data = new ApplicationData([]);

        static::assertTrue($data->isEmpty());
    }

    public function test_that_count_returns_expected_count()
    {
        $data = new ApplicationData(['foo' => 'bar', 'baz' => 'buz']);

        static::assertCount(2, $data);
    }

    public function test_that_it_is_iterable()
    {
        $data = new ApplicationData(['foo' => 'bar', 'baz' => 'buz']);

        $count = 0;
        foreach ($data as $name => $value) {
            $count++;
        }

        static::assertSame(2, $count);
    }

    public function test_that_to_array_returns_expected_value()
    {
        $input = ['foo' => 'bar', 'baz' => 'buz'];

        $data = new ApplicationData($input);

        static::assertSame($input, $data->toArray());
    }
}
