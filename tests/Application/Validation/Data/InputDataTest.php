<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Application\Validation\Data;

use Novuso\Common\Application\Validation\Data\InputData;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Validation\Data\InputData
 */
class InputDataTest extends UnitTestCase
{
    public function test_that_get_returns_expected_value()
    {
        $data = new InputData(['foo' => 'bar']);

        static::assertSame('bar', $data->get('foo'));
    }

    public function test_that_has_returns_true_when_key_is_defined()
    {
        $data = new InputData(['foo' => 'bar']);

        static::assertTrue($data->has('foo'));
    }

    public function test_that_has_returns_false_when_key_is_not_defined()
    {
        $data = new InputData([]);

        static::assertFalse($data->has('foo'));
    }

    public function test_that_is_empty_returns_true_for_empty_collection()
    {
        $data = new InputData([]);

        static::assertTrue($data->isEmpty());
    }

    public function test_that_count_returns_expected_count()
    {
        $data = new InputData(['foo' => 'bar', 'baz' => 'buz']);

        static::assertCount(2, $data);
    }

    public function test_that_it_is_iterable()
    {
        $data = new InputData(['foo' => 'bar', 'baz' => 'buz']);

        $count = 0;
        foreach ($data as $name => $value) {
            $count++;
        }

        static::assertSame(2, $count);
    }

    public function test_that_to_array_returns_expected_value()
    {
        $input = ['foo' => 'bar', 'baz' => 'buz'];

        $data = new InputData($input);

        static::assertSame($input, $data->toArray());
    }
}
