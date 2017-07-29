<?php

namespace Novuso\Test\Common\Domain\Type;

use Novuso\Test\Common\Resources\Domain\Type\FullName;
use Novuso\Test\System\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Domain\Type\ValueObject
 */
class ValueObjectTest extends UnitTestCase
{
    public function test_that_static_constructor_returns_expected_instance()
    {
        $fullName = FullName::fromString('John R Nickell');
        $this->assertSame('John R Nickell', $fullName->toString());
    }

    public function test_that_string_cast_returns_expected_value()
    {
        $fullName = FullName::fromParts('John', 'Nickell', 'R');
        $this->assertSame('John R Nickell', (string) $fullName);
    }

    public function test_that_it_is_json_encodable()
    {
        $fullName = FullName::fromParts('John', 'Nickell', 'R');
        $data = ['name' => $fullName];
        $this->assertSame('{"name":"John R Nickell"}', json_encode($data));
    }

    public function test_that_it_is_serializable()
    {
        $state = serialize(FullName::fromParts('John', 'Nickell', 'R'));
        $fullName = unserialize($state);
        $this->assertSame('John R Nickell', (string) $fullName);
    }

    public function test_that_equals_returns_true_for_same_instance()
    {
        $fullName = FullName::fromParts('John', 'Nickell');
        $this->assertTrue($fullName->equals($fullName));
    }

    public function test_that_equals_returns_true_for_same_value()
    {
        $fullName1 = FullName::fromParts('John', 'Nickell');
        $fullName2 = FullName::fromParts('John', 'Nickell');
        $this->assertTrue($fullName1->equals($fullName2));
    }

    public function test_that_equals_returns_false_for_invalid_type()
    {
        $fullName = FullName::fromParts('John', 'Nickell');
        $this->assertFalse($fullName->equals('John Nickell'));
    }

    public function test_that_equals_returns_false_for_unequal_value()
    {
        $fullName1 = FullName::fromParts('John', 'Nickell');
        $fullName2 = FullName::fromParts('John', 'Nickell', 'R');
        $this->assertFalse($fullName1->equals($fullName2));
    }

    public function test_that_hash_value_returns_expected_string()
    {
        $fullName = FullName::fromParts('John', 'Nickell');
        $this->assertSame('John Nickell', $fullName->hashValue());
    }
}
