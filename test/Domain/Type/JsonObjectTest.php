<?php declare(strict_types=1);

namespace Novuso\Test\Common\Domain\Type;

use Novuso\Common\Domain\Type\JsonObject;
use Novuso\Test\System\TestCase\UnitTestCase;

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
        $json = JsonObject::fromData((['foo' => 'bar']));
        $this->assertSame('{"foo":"bar"}', $json->toString());
    }

    public function test_that_from_string_returns_expected_instance()
    {
        $json = JsonObject::fromString('{"foo":"bar"}');
        $this->assertSame('{"foo":"bar"}', $json->toString());
    }

    public function test_that_to_data_returns_expected_value()
    {
        $json = JsonObject::fromString('["foo","bar"]');
        $this->assertSame(['foo', 'bar'], $json->toData());
    }

    public function test_that_it_is_json_encodable()
    {
        $data = ['config' => JsonObject::fromData(['foo' => 'bar'])];
        $this->assertSame('{"config":{"foo":"bar"}}', json_encode($data));
    }

    /**
     * @expectedException \Novuso\System\Exception\DomainException
     */
    public function test_that_constructor_throws_exception_for_invalid_data()
    {
        new JsonObject(pack('H*', 'c32e'));
    }

    /**
     * @expectedException \Novuso\System\Exception\DomainException
     */
    public function test_that_from_string_throws_exception_for_invalid_string()
    {
        JsonObject::fromString('{"data":{"key":{"value":{}}}');
    }
}
