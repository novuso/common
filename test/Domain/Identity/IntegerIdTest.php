<?php

namespace Novuso\Test\Common\Domain\Identity;

use Novuso\Test\Common\Resources\Domain\Identity\NumericId;
use Novuso\Test\System\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Domain\Identity\IntegerId
 */
class IntegerIdTest extends UnitTestCase
{
    public function test_that_from_string_returns_expected_instance()
    {
        $numericId = NumericId::fromString('20');
        $this->assertSame(20, $numericId->toInt());
    }

    public function test_that_from_int_returns_expected_instance()
    {
        $numericId = NumericId::fromInt(20);
        $this->assertSame(20, $numericId->toInt());
    }

    public function test_that_string_cast_returns_expected_value()
    {
        $numericId = NumericId::fromInt(20);
        $this->assertSame('20', (string) $numericId);
    }

    public function test_that_compare_to_returns_zero_for_same_instance()
    {
        $numericId = NumericId::fromInt(20);
        $this->assertSame(0, $numericId->compareTo($numericId));
    }

    public function test_that_compare_to_returns_zero_for_same_value()
    {
        $numericId1 = NumericId::fromInt(20);
        $numericId2 = NumericId::fromInt(20);
        $this->assertSame(0, $numericId1->compareTo($numericId2));
    }

    public function test_that_compare_to_returns_one_for_greater_value()
    {
        $numericId1 = NumericId::fromInt(21);
        $numericId2 = NumericId::fromInt(20);
        $this->assertSame(1, $numericId1->compareTo($numericId2));
    }

    public function test_that_compare_to_returns_neg_one_for_lesser_value()
    {
        $numericId1 = NumericId::fromInt(20);
        $numericId2 = NumericId::fromInt(21);
        $this->assertSame(-1, $numericId1->compareTo($numericId2));
    }

    public function test_that_equals_returns_true_for_same_instance()
    {
        $numericId = NumericId::fromInt(20);
        $this->assertTrue($numericId->equals($numericId));
    }

    public function test_that_equals_returns_true_for_same_value()
    {
        $numericId1 = NumericId::fromInt(20);
        $numericId2 = NumericId::fromInt(20);
        $this->assertTrue($numericId1->equals($numericId2));
    }

    public function test_that_equals_returns_false_for_invalid_type()
    {
        $numericId = NumericId::fromInt(20);
        $this->assertFalse($numericId->equals(20));
    }

    public function test_that_equals_returns_false_for_unequal_value()
    {
        $numericId1 = NumericId::fromInt(20);
        $numericId2 = NumericId::fromInt(21);
        $this->assertFalse($numericId1->equals($numericId2));
    }

    public function test_that_hash_value_returns_expected_string()
    {
        $numericId = NumericId::fromInt(20);
        $this->assertSame('20', $numericId->hashValue());
    }

    /**
     * @expectedException \Novuso\System\Exception\DomainException
     */
    public function test_that_from_string_throws_exception_for_invalid_value()
    {
        NumericId::fromString('@42');
    }

    /**
     * @expectedException \Novuso\System\Exception\DomainException
     */
    public function test_that_guard_id_is_called_from_constructor_for_validation()
    {
        NumericId::fromInt(-20);
    }

    /**
     * @expectedException \AssertionError
     */
    public function test_that_compare_to_throws_exception_for_invalid_argument()
    {
        $numericId = NumericId::fromInt(20);
        $numericId->compareTo(20);
    }
}
