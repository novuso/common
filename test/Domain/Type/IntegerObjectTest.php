<?php

namespace Novuso\Test\Common\Domain\Type;

use Novuso\Common\Domain\Type\IntegerObject;
use Novuso\Test\System\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Domain\Type\IntegerObject
 */
class IntegerObjectTest extends UnitTestCase
{
    public function test_that_constructor_takes_integer_value()
    {
        $integer = new IntegerObject(42);
        $this->assertSame(42, $integer->value());
    }

    public function test_that_from_string_returns_expected_instance()
    {
        $integer = IntegerObject::fromString('2017');
        $this->assertSame(2017, $integer->value());
    }

    public function test_that_create_returns_expected_instance()
    {
        $integer = IntegerObject::create(42);
        $this->assertSame(42, $integer->value());
    }

    public function test_that_is_zero_returns_true_for_zero_value()
    {
        $integer = IntegerObject::create(0);
        $this->assertTrue($integer->isZero());
    }

    public function test_that_is_zero_returns_false_for_non_zero_value()
    {
        $integer = IntegerObject::create(-1);
        $this->assertFalse($integer->isZero());
    }

    public function test_that_is_positive_returns_true_for_positive_value()
    {
        $integer = IntegerObject::create(1);
        $this->assertTrue($integer->isPositive());
    }

    public function test_that_is_positive_returns_false_for_negative_value()
    {
        $integer = IntegerObject::create(-1);
        $this->assertFalse($integer->isPositive());
    }

    public function test_that_is_negative_returns_true_for_negative_value()
    {
        $integer = IntegerObject::create(-1);
        $this->assertTrue($integer->isNegative());
    }

    public function test_that_is_negative_returns_false_for_positive_value()
    {
        $integer = IntegerObject::create(1);
        $this->assertFalse($integer->isNegative());
    }

    public function test_that_is_even_returns_true_for_even_value()
    {
        $integer = IntegerObject::create(512);
        $this->assertTrue($integer->isEven());
    }

    public function test_that_is_even_returns_false_for_odd_value()
    {
        $integer = IntegerObject::create(361);
        $this->assertFalse($integer->isEven());
    }

    public function test_that_is_odd_returns_true_for_odd_value()
    {
        $integer = IntegerObject::create(-517);
        $this->assertTrue($integer->isOdd());
    }

    public function test_that_is_odd_returns_false_for_even_value()
    {
        $integer = IntegerObject::create(1024);
        $this->assertFalse($integer->isOdd());
    }

    public function test_that_abs_returns_expected_instance_for_positive_value()
    {
        $integer = IntegerObject::create(1);
        $this->assertSame(1, $integer->abs()->value());
    }

    public function test_that_abs_returns_expected_instance_for_negative_value()
    {
        $integer = IntegerObject::create(-1);
        $this->assertSame(1, $integer->abs()->value());
    }

    public function test_that_to_string_returns_expected_value()
    {
        $integer = IntegerObject::create(42);
        $this->assertSame('42', $integer->toString());
    }

    public function test_that_to_binary_returns_expected_value()
    {
        $integer = IntegerObject::create(42);
        $this->assertSame('101010', $integer->toBinary());
    }

    public function test_that_to_hexadecimal_returns_expected_value()
    {
        $integer = IntegerObject::create(42);
        $this->assertSame('2a', $integer->toHexadecimal());
    }

    public function test_that_to_octal_returns_expected_value()
    {
        $integer = IntegerObject::create(42);
        $this->assertSame('52', $integer->toOctal());
    }

    public function test_that_equals_returns_true_for_same_instance()
    {
        $integer = IntegerObject::create(42);
        $this->assertTrue($integer->equals($integer));
    }

    public function test_that_equals_returns_true_for_same_value()
    {
        $integer1 = IntegerObject::create(42);
        $integer2 = IntegerObject::create(42);
        $this->assertTrue($integer1->equals($integer2));
    }

    public function test_that_equals_returns_false_for_different_value()
    {
        $integer1 = IntegerObject::create(42);
        $integer2 = IntegerObject::create(43);
        $this->assertFalse($integer1->equals($integer2));
    }

    public function test_that_equals_returns_false_for_invalid_type()
    {
        $integer = IntegerObject::create(42);
        $this->assertFalse($integer->equals(42));
    }

    public function test_that_compare_to_returns_zero_for_same_instance()
    {
        $integer = IntegerObject::create(42);
        $this->assertSame(0, $integer->compareTo($integer));
    }

    public function test_that_compare_to_returns_zero_for_same_value()
    {
        $integer1 = IntegerObject::create(42);
        $integer2 = IntegerObject::create(42);
        $this->assertSame(0, $integer1->compareTo($integer2));
    }

    public function test_that_compare_to_returns_pos_one_for_greater_value()
    {
        $integer1 = IntegerObject::create(43);
        $integer2 = IntegerObject::create(42);
        $this->assertSame(1, $integer1->compareTo($integer2));
    }

    public function test_that_compare_to_returns_neg_one_for_lesser_value()
    {
        $integer1 = IntegerObject::create(42);
        $integer2 = IntegerObject::create(43);
        $this->assertSame(-1, $integer1->compareTo($integer2));
    }

    /**
     * @expectedException \Novuso\System\Exception\DomainException
     */
    public function test_that_from_string_throws_exception_for_invalid_string()
    {
        IntegerObject::fromString('@1492');
    }
}
