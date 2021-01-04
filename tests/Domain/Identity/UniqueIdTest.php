<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Domain\Identity;

use Novuso\Common\Domain\Value\Identifier\Uuid;
use Novuso\Common\Test\Resources\Domain\Identity\ThingId;
use Novuso\System\Exception\AssertionException;
use Novuso\System\Test\TestCase\UnitTestCase;
use Novuso\System\Utility\ClassName;

/**
 * @covers \Novuso\Common\Domain\Identity\UniqueId
 */
class UniqueIdTest extends UnitTestCase
{
    public function test_that_generate_returns_expected_instance()
    {
        $thingId = ThingId::generate();
        static::assertTrue(Uuid::isValid($thingId->toString()));
    }

    public function test_that_from_string_returns_expected_instance()
    {
        $thingId = ThingId::fromString('014ea33a-d902-4025-8b53-01191406579d');
        static::assertSame('014ea33a-d902-4025-8b53-01191406579d', $thingId->toString());
    }

    public function test_that_string_cast_returns_expected_value()
    {
        $thingId = ThingId::fromString('014ea33a-d902-4025-8b53-01191406579d');
        static::assertSame('014ea33a-d902-4025-8b53-01191406579d', (string) $thingId);
    }

    public function test_that_compare_to_returns_zero_for_same_instance()
    {
        $thingId = ThingId::fromString('014ea33a-d902-4025-8b53-01191406579d');
        static::assertSame(0, $thingId->compareTo($thingId));
    }

    public function test_that_compare_to_returns_zero_for_same_value()
    {
        $thingId1 = ThingId::fromString('014ea33a-d902-4025-8b53-01191406579d');
        $thingId2 = ThingId::fromString('014ea33a-d902-4025-8b53-01191406579d');
        static::assertSame(0, $thingId1->compareTo($thingId2));
    }

    public function test_that_compare_to_returns_one_for_greater_value()
    {
        $thingId1 = ThingId::fromString('014ea33b-65bc-4d53-a9f4-161c8ea937a2');
        $thingId2 = ThingId::fromString('014ea33a-d902-4025-8b53-01191406579d');
        static::assertSame(1, $thingId1->compareTo($thingId2));
    }

    public function test_that_compare_to_returns_neg_one_for_lesser_value()
    {
        $thingId1 = ThingId::fromString('014ea33a-d902-4025-8b53-01191406579d');
        $thingId2 = ThingId::fromString('014ea33b-65bc-4d53-a9f4-161c8ea937a2');
        static::assertSame(-1, $thingId1->compareTo($thingId2));
    }

    public function test_that_equals_returns_true_for_same_instance()
    {
        $thingId = ThingId::fromString('014ea33a-d902-4025-8b53-01191406579d');
        static::assertTrue($thingId->equals($thingId));
    }

    public function test_that_equals_returns_true_for_same_value()
    {
        $thingId1 = ThingId::fromString('014ea33a-d902-4025-8b53-01191406579d');
        $thingId2 = ThingId::fromString('014ea33a-d902-4025-8b53-01191406579d');
        static::assertTrue($thingId1->equals($thingId2));
    }

    public function test_that_equals_returns_false_for_invalid_type()
    {
        $thingId = ThingId::fromString('014ea33a-d902-4025-8b53-01191406579d');
        static::assertFalse($thingId->equals('014ea33a-d902-4025-8b53-01191406579d'));
    }

    public function test_that_equals_returns_false_for_unequal_value()
    {
        $thingId1 = ThingId::fromString('014ea33a-d902-4025-8b53-01191406579d');
        $thingId2 = ThingId::fromString('014ea33b-65bc-4d53-a9f4-161c8ea937a2');
        static::assertFalse($thingId1->equals($thingId2));
    }

    public function test_that_hash_value_returns_expected_string()
    {
        $thingId = ThingId::fromString('014ea33a-d902-4025-8b53-01191406579d');
        static::assertSame(
            sprintf(
                '%s:014ea33ad90240258b5301191406579d',
                ClassName::canonical(ThingId::class)
            ),
            $thingId->hashValue()
        );
    }

    public function test_that_compare_to_throws_exception_for_invalid_argument()
    {
        $this->expectException(AssertionException::class);

        $thingId = ThingId::fromString('014ea33a-d902-4025-8b53-01191406579d');
        $thingId->compareTo('014ea33a-d902-4025-8b53-01191406579d');
    }
}
