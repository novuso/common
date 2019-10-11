<?php declare(strict_types=1);

namespace Novuso\Common\Test\Domain\Type;

use Novuso\Common\Domain\Type\JsonObject;
use Novuso\System\Exception\DomainException;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Domain\Type\JsonObject
 */
class JsonObjectTest extends UnitTestCase
{
    public function test_that_constructor_takes_data_value()
    {
        $json = new JsonObject(['foo' => 'bar']);
        $this->assertSame('{"foo":"bar"}', $json->toString());
    }

    public function test_that_from_data_returns_expected_instance()
    {
        $json = JsonObject::fromData(['foo' => 'bar']);
        $this->assertSame('{"foo":"bar"}', $json->toString());
    }

    public function test_that_from_string_returns_expected_instance()
    {
        $json = JsonObject::fromString('{"foo":"bar"}');
        $this->assertSame(['foo' => 'bar'], $json->toData());
    }

    public function test_that_it_is_json_encodable()
    {
        $json = JsonObject::fromData(['foo' => 'bar']);
        $this->assertSame('{"foo":"bar"}', json_encode($json));
    }

    public function test_that_pretty_print_returns_expected_value()
    {
        $json = JsonObject::fromData([
            'config' => [
                'db' => [
                    'host'     => 'localhost',
                    'port'     => 3306,
                    'database' => 'app',
                    'username' => 'root',
                    'password' => 'secret'
                ]
            ]
        ]);
        $expected = <<<EOF
{
    "config": {
        "db": {
            "host": "localhost",
            "port": 3306,
            "database": "app",
            "username": "root",
            "password": "secret"
        }
    }
}
EOF;

        $this->assertSame($expected, $json->prettyPrint());
    }

    public function test_that_string_cast_returns_expected_string()
    {
        $json = JsonObject::fromData(['foo' => 'bar']);
        $this->assertSame('{"foo":"bar"}', (string) $json);
    }

    public function test_that_equals_returns_true_for_same_instance()
    {
        $json = JsonObject::fromData(['foo' => 'bar']);
        $this->assertTrue($json->equals($json));
    }

    public function test_that_equals_returns_true_for_same_value()
    {
        $json1 = JsonObject::fromData(['foo' => 'bar']);
        $json2 = JsonObject::fromData(['foo' => 'bar']);
        $this->assertTrue($json1->equals($json2));
    }

    public function test_that_equals_returns_false_for_different_value()
    {
        $json1 = JsonObject::fromData(['foo' => 'bar']);
        $json2 = JsonObject::fromData(['foo' => 'BAR']);
        $this->assertFalse($json1->equals($json2));
    }

    public function test_that_equals_returns_false_for_invalid_type()
    {
        $json = JsonObject::fromData(['foo' => 'bar']);
        $this->assertFalse($json->equals('{"foo":"bar"}'));
    }

    public function test_that_hash_value_returns_expected_string()
    {
        $value = '{"foo":"bar"}';
        $json = JsonObject::fromData(['foo' => 'bar']);
        $this->assertSame($value, $json->hashValue());
    }

    public function test_that_constructor_throws_exception_for_invalid_data()
    {
        $this->expectException(DomainException::class);

        $resource = fopen(__FILE__, 'r');
        try {
            new JsonObject($resource);
            fclose($resource);
        } catch (\Throwable $e) {
            fclose($resource);
            throw $e;
        }
    }

    public function test_that_from_string_throws_exception_for_invalid_string()
    {
        $this->expectException(DomainException::class);

        JsonObject::fromString('{"foo":"bar"');
    }
}
