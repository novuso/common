<?php

namespace Novuso\Test\Common\Domain\Type;

use Novuso\Common\Domain\Type\FloatObject;
use Novuso\Common\Domain\Type\RoundingMode;
use Novuso\Test\System\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Domain\Type\FloatObject
 */
class FloatObjectTest extends UnitTestCase
{
    public function test_that_constructor_takes_float_value()
    {
        $float = new FloatObject(3.14);
        $this->assertSame(3.14, $float->value());
    }

    public function test_that_from_string_returns_expected_instance()
    {
        $float = FloatObject::fromString('0.0143764');
        $this->assertSame(0.0143764, $float->value());
    }

    public function test_that_create_returns_expected_instance()
    {
        $float = FloatObject::create(3.14);
        $this->assertSame(3.14, $float->value());
    }

    public function test_that_is_zero_returns_true_for_zero_value()
    {
        $float = FloatObject::create(0.00);
        $this->assertTrue($float->isZero());
    }

    public function test_that_is_zero_returns_false_for_non_zero_value()
    {
        $float = FloatObject::create(-1.0);
        $this->assertFalse($float->isZero());
    }

    public function test_that_is_positive_returns_true_for_positive_value()
    {
        $float = FloatObject::create(1.0);
        $this->assertTrue($float->isPositive());
    }

    public function test_that_is_positive_returns_false_for_negative_value()
    {
        $float = FloatObject::create(-1.0);
        $this->assertFalse($float->isPositive());
    }

    public function test_that_is_negative_returns_true_for_negative_value()
    {
        $float = FloatObject::create(-1.0);
        $this->assertTrue($float->isNegative());
    }

    public function test_that_is_negative_returns_false_for_positive_value()
    {
        $float = FloatObject::create(1.0);
        $this->assertFalse($float->isNegative());
    }

    public function test_that_abs_returns_expected_instance_for_positive_value()
    {
        $float = FloatObject::create(1.0);
        $this->assertSame(1.0, $float->abs()->value());
    }

    public function test_that_abs_returns_expected_instance_for_negative_value()
    {
        $float = FloatObject::create(-1.0);
        $this->assertSame(1.0, $float->abs()->value());
    }

    public function test_that_ceil_returns_expected_instance()
    {
        $float = FloatObject::create(3.14);
        $this->assertSame(4.0, $float->ceil()->value());
    }

    public function test_that_floor_returns_expected_instance()
    {
        $float = FloatObject::create(3.14);
        $this->assertSame(3.0, $float->floor()->value());
    }

    public function test_that_round_returns_expected_instance_simple()
    {
        $float = FloatObject::create(3.515);
        $this->assertSame(4.0, $float->round()->value());
    }

    public function test_that_round_returns_expected_instance_with_precision()
    {
        $float = FloatObject::create(3.515);
        $this->assertSame(3.52, $float->round(2)->value());
    }

    public function test_that_round_returns_expected_instance_with_precision_down()
    {
        $float = FloatObject::create(3.515);
        $this->assertSame(3.51, $float->round(2, RoundingMode::HALF_DOWN())->value());
    }

    public function test_that_round_returns_expected_instance_with_precision_even()
    {
        $float = FloatObject::create(3.515);
        $this->assertSame(3.52, $float->round(2, RoundingMode::HALF_EVEN())->value());
    }

    public function test_that_round_returns_expected_instance_with_precision_odd()
    {
        $float = FloatObject::create(3.515);
        $this->assertSame(3.51, $float->round(2, RoundingMode::HALF_ODD())->value());
    }

    public function test_that_to_string_returns_expected_value()
    {
        $float = FloatObject::create(0.0143764);
        $this->assertSame('0.0143764', $float->toString());
    }

    public function test_that_equals_returns_true_for_same_instance()
    {
        $float = FloatObject::create(3.14);
        $this->assertTrue($float->equals($float));
    }

    public function test_that_equals_returns_true_for_same_value()
    {
        $float1 = FloatObject::create(3.14);
        $float2 = FloatObject::create(3.14);
        $this->assertTrue($float1->equals($float2));
    }

    public function test_that_equals_returns_false_for_different_value()
    {
        $float1 = FloatObject::create(3.14159265359);
        $float2 = FloatObject::create(3.14159265358);
        $this->assertFalse($float1->equals($float2));
    }

    public function test_that_equals_returns_false_for_invalid_type()
    {
        $float = FloatObject::create(3.14);
        $this->assertFalse($float->equals(3.14));
    }

    public function test_that_compare_to_returns_zero_for_same_instance()
    {
        $float = FloatObject::create(3.14);
        $this->assertSame(0, $float->compareTo($float));
    }

    public function test_that_compare_to_returns_zero_for_same_value()
    {
        $float1 = FloatObject::create(3.14);
        $float2 = FloatObject::create(3.14);
        $this->assertSame(0, $float1->compareTo($float2));
    }

    public function test_that_compare_to_returns_pos_one_for_greater_value()
    {
        $float1 = FloatObject::create(3.15);
        $float2 = FloatObject::create(3.14);
        $this->assertSame(1, $float1->compareTo($float2));
    }

    public function test_that_compare_to_returns_neg_one_for_lesser_value()
    {
        $float1 = FloatObject::create(3.14);
        $float2 = FloatObject::create(3.15);
        $this->assertSame(-1, $float1->compareTo($float2));
    }

    /**
     * @expectedException \Novuso\System\Exception\DomainException
     */
    public function test_that_from_string_throws_exception_for_invalid_string()
    {
        FloatObject::fromString('$13.75');
    }
}
