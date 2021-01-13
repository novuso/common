<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Domain\Value\Identifier;

use Novuso\Common\Domain\Value\Identifier\Uuid;
use Novuso\System\Exception\AssertionException;
use Novuso\System\Exception\DomainException;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Domain\Value\Identifier\Uuid
 */
class UuidTest extends UnitTestCase
{
    public function test_that_random_returns_uuid4_random_instance()
    {
        $uuid = Uuid::random();

        static::assertSame(Uuid::VERSION_RANDOM, $uuid->version());
    }

    public function test_that_comb_returns_uuid4_random_instance()
    {
        $uuid = Uuid::comb();

        static::assertSame(Uuid::VERSION_RANDOM, $uuid->version());
    }

    public function test_that_comb_timestamp_covers_most_significant_bits_by_default()
    {
        $uuid1 = Uuid::comb();
        $uuid2 = Uuid::comb();
        $timeLow1 = $uuid1->timeLow();
        $timeLow2 = $uuid2->timeLow();

        static::assertSame(substr($timeLow1, 0, 4), substr($timeLow2, 0, 4));
    }

    public function test_that_comb_timestamp_covers_least_significant_bits_with_flag()
    {
        $uuid1 = Uuid::comb(false);
        $uuid2 = Uuid::comb(false);
        $node1 = $uuid1->node();
        $node2 = $uuid2->node();

        static::assertSame(substr($node1, 0, 4), substr($node2, 0, 4));
    }

    public function test_that_time_returns_uuid_according_to_client_provided_values()
    {
        // For more information about the basic algorithm and how to use the
        // clock sequence: http://tools.ietf.org/html/rfc4122#section-4.2.1
        //
        // The 60-bit timestamp (as a string) can be retrieved by calling the
        // Uuid::timestamp() method.
        $timestamp = Uuid::timestamp();
        $node = '48:2C:6A:1E:59:3D';
        $clockSeq = 0x398B;
        $expected = sprintf(
            '%s-%s-%s-b98b-482c6a1e593d',
            substr($timestamp, 8, 8),
            substr($timestamp, 4, 4),
            '1'.substr($timestamp, 1, 3)
        );
        $uuid = Uuid::time($node, $clockSeq, $timestamp);

        static::assertSame($expected, $uuid->toString());
    }

    public function test_that_time_will_randomize_values_not_provided()
    {
        // NOTE: This is not the recommended way to use this method
        //       See method comments and favor providing values as shown above
        $uuid = Uuid::time();

        static::assertSame(Uuid::VERSION_TIME, $uuid->version());
    }

    public function test_that_named_returns_expected_instance()
    {
        $expected = '77f19926-3322-5602-871d-febea455b335';
        $uuid = Uuid::named(Uuid::NAMESPACE_URL, 'https://www.google.com');

        static::assertSame($expected, $uuid->toString());
    }

    public function test_that_md5_returns_expected_instance()
    {
        $expected = 'd39a36cc-b262-3c67-a6ca-0168e948bdd4';
        $uuid = Uuid::md5(Uuid::NAMESPACE_URL, 'https://www.google.com');

        static::assertSame($expected, $uuid->toString());
    }

    public function test_that_from_string_returns_expected_instance()
    {
        $uuid = Uuid::fromString('{71967621-A924-4C58-A8F2-E073967744E0}');

        static::assertSame('71967621-a924-4c58-a8f2-e073967744e0', $uuid->toString());
    }

    public function test_that_parse_returns_expected_instance()
    {
        $uuid = Uuid::parse('{71967621-A924-4C58-A8F2-E073967744E0}');

        static::assertSame('71967621-a924-4c58-a8f2-e073967744e0', $uuid->toString());
    }

    public function test_that_from_hex_returns_expected_instance()
    {
        $uuid = Uuid::fromHex('71967621A9244C58A8F2E073967744E0');

        static::assertSame('71967621a9244c58a8f2e073967744e0', $uuid->toHex());
    }

    public function test_that_from_bytes_returns_expected_instance()
    {
        $bytes = base64_decode('cZZ2IakkTFio8uBzlndE4A==');
        $uuid = Uuid::fromBytes($bytes);

        static::assertSame($bytes, $uuid->toBytes());
    }

    public function test_that_is_valid_returns_true_for_valid_uuid_string()
    {
        static::assertTrue(Uuid::isValid('5f32efa0-2f13-4fcf-87d6-86c86a734247'));
    }

    public function test_that_is_valid_returns_false_for_invalid_uuid_string()
    {
        static::assertFalse(Uuid::isValid('5f32efa0-2f13-4fcf-87d686-c86a734247'));
    }

    public function test_that_is_valid_returns_false_for_invalid_type()
    {
        static::assertFalse(Uuid::isValid('71967621-a924-4c58-a8f2e073-967744e0'));
    }

    public function test_that_time_low_returns_expected_value()
    {
        $uuid = Uuid::parse('5f32efa0-2f13-4fcf-87d6-86c86a734247');

        static::assertSame('5f32efa0', $uuid->timeLow());
    }

    public function test_that_time_mid_returns_expected_value()
    {
        $uuid = Uuid::parse('5f32efa0-2f13-4fcf-87d6-86c86a734247');

        static::assertSame('2f13', $uuid->timeMid());
    }

    public function test_that_time_hi_and_version_returns_expected_value()
    {
        $uuid = Uuid::parse('5f32efa0-2f13-4fcf-87d6-86c86a734247');

        static::assertSame('4fcf', $uuid->timeHiAndVersion());
    }

    public function test_that_clock_seq_hi_and_reserved_returns_expected_value()
    {
        $uuid = Uuid::parse('5f32efa0-2f13-4fcf-87d6-86c86a734247');

        static::assertSame('87', $uuid->clockSeqHiAndReserved());
    }

    public function test_that_clock_seq_low_returns_expected_value()
    {
        $uuid = Uuid::parse('5f32efa0-2f13-4fcf-87d6-86c86a734247');

        static::assertSame('d6', $uuid->clockSeqLow());
    }

    public function test_that_node_returns_expected_value()
    {
        $uuid = Uuid::parse('5f32efa0-2f13-4fcf-87d6-86c86a734247');

        static::assertSame('86c86a734247', $uuid->node());
    }

    public function test_that_most_significant_bits_returns_expected_value()
    {
        $uuid = Uuid::fromHex('71967621a9244c58a8f2e073967744e0');

        static::assertSame('71967621a9244c58', $uuid->mostSignificantBits());
    }

    public function test_that_least_significant_bits_returns_expected_value()
    {
        $uuid = Uuid::fromHex('71967621a9244c58a8f2e073967744e0');

        static::assertSame('a8f2e073967744e0', $uuid->leastSignificantBits());
    }

    public function test_that_version_returns_time_constant_for_version_1()
    {
        $uuid = Uuid::parse('{71967621-A924-1C58-A8F2-E073967744E0}');

        static::assertSame(Uuid::VERSION_TIME, $uuid->version());
    }

    public function test_that_version_returns_dce_constant_for_version_2()
    {
        $uuid = Uuid::parse('{71967621-A924-2C58-A8F2-E073967744E0}');

        static::assertSame(Uuid::VERSION_DCE, $uuid->version());
    }

    public function test_that_version_returns_md5_constant_for_version_3()
    {
        $uuid = Uuid::parse('{71967621-A924-3C58-A8F2-E073967744E0}');

        static::assertSame(Uuid::VERSION_MD5, $uuid->version());
    }

    public function test_that_version_returns_random_constant_for_version_4()
    {
        $uuid = Uuid::parse('{71967621-A924-4C58-A8F2-E073967744E0}');

        static::assertSame(Uuid::VERSION_RANDOM, $uuid->version());
    }

    public function test_that_version_returns_sha1_constant_for_version_5()
    {
        $uuid = Uuid::parse('{71967621-A924-5C58-A8F2-E073967744E0}');

        static::assertSame(Uuid::VERSION_SHA1, $uuid->version());
    }

    public function test_that_version_returns_unknown_constant_for_nil()
    {
        $uuid = Uuid::parse(Uuid::NIL);

        static::assertSame(Uuid::VERSION_UNKNOWN, $uuid->version());
    }

    public function test_that_variant_returns_ncs_constant_for_variant_0()
    {
        $uuid = Uuid::parse(Uuid::NIL);

        static::assertSame(Uuid::VARIANT_RESERVED_NCS, $uuid->variant());
    }

    public function test_that_variant_returns_4122_constant_for_generated_uuids()
    {
        $uuid = Uuid::random();

        static::assertSame(Uuid::VARIANT_RFC_4122, $uuid->variant());
    }

    public function test_that_variant_returns_microsoft_constant_for_variant_6()
    {
        $uuid = Uuid::parse('{71967621-A924-4C58-C8F2-E073967744E0}');

        static::assertSame(Uuid::VARIANT_RESERVED_MICROSOFT, $uuid->variant());
    }

    public function test_that_variant_returns_future_constant_for_variant_7()
    {
        $uuid = Uuid::parse('{71967621-A924-4C58-E8F2-E073967744E0}');

        static::assertSame(Uuid::VARIANT_RESERVED_FUTURE, $uuid->variant());
    }

    public function test_that_to_urn_returns_expected_value()
    {
        $uuid = Uuid::parse('71967621-a924-4c58-a8f2-e073967744e0');

        static::assertSame('urn:uuid:71967621-a924-4c58-a8f2-e073967744e0', $uuid->toUrn());
    }

    public function test_that_to_array_returns_underscored_fields_as_expected()
    {
        // Fields should be returned as described in the Layout and Byte Order
        // section of RFC 4122 http://tools.ietf.org/html/rfc4122#section-4.1.2
        $uuid = Uuid::parse('71967621-a924-4c58-a8f2-e073967744e0');
        $expected = [
            'time_low'                  => '71967621',
            'time_mid'                  => 'a924',
            'time_hi_and_version'       => '4c58',
            'clock_seq_hi_and_reserved' => 'a8',
            'clock_seq_low'             => 'f2',
            'node'                      => 'e073967744e0'
        ];

        static::assertSame($expected, $uuid->toArray());
    }

    public function test_that_compare_to_returns_zero_for_same_instance()
    {
        $uuid = Uuid::parse('71967621-a924-4c58-a8f2-e073967744e0');

        static::assertSame(0, $uuid->compareTo($uuid));
    }

    public function test_that_compare_to_returns_zero_for_same_value()
    {
        $uuid1 = Uuid::parse('71967621-a924-4c58-a8f2-e073967744e0');
        $uuid2 = Uuid::parse('71967621-a924-4c58-a8f2-e073967744e0');

        static::assertSame(0, $uuid1->compareTo($uuid2));
    }

    public function test_that_compare_to_returns_one_for_greater_msb()
    {
        $uuid1 = Uuid::parse('71967621-a924-4c59-a8f2-e073967744e0');
        $uuid2 = Uuid::parse('71967621-a924-4c58-a8f2-e073967744e0');

        static::assertSame(1, $uuid1->compareTo($uuid2));
    }

    public function test_that_compare_to_returns_one_for_greater_lsb()
    {
        $uuid1 = Uuid::parse('71967621-a924-4c58-a8f2-e073967744e1');
        $uuid2 = Uuid::parse('71967621-a924-4c58-a8f2-e073967744e0');

        static::assertSame(1, $uuid1->compareTo($uuid2));
    }

    public function test_that_compare_to_returns_neg_one_for_lesser_msb()
    {
        $uuid1 = Uuid::parse('71967621-a924-4c58-a8f2-e073967744e0');
        $uuid2 = Uuid::parse('71967621-a924-4c59-a8f2-e073967744e0');

        static::assertSame(-1, $uuid1->compareTo($uuid2));
    }

    public function test_that_compare_to_returns_neg_one_for_lesser_lsb()
    {
        $uuid1 = Uuid::parse('71967621-a924-4c58-a8f2-e073967744e0');
        $uuid2 = Uuid::parse('71967621-a924-4c58-a8f2-e073967744e1');

        static::assertSame(-1, $uuid1->compareTo($uuid2));
    }

    public function test_that_equals_returns_true_for_same_instance()
    {
        $uuid = Uuid::parse('5f32efa0-2f13-4fcf-87d6-86c86a734247');

        static::assertTrue($uuid->equals($uuid));
    }

    public function test_that_equals_returns_true_for_same_value()
    {
        $uuid1 = Uuid::parse('5f32efa0-2f13-4fcf-87d6-86c86a734247');
        $uuid2 = Uuid::parse('5f32efa0-2f13-4fcf-87d6-86c86a734247');

        static::assertTrue($uuid1->equals($uuid2));
    }

    public function test_that_equals_returns_false_for_invalid_type()
    {
        $uuid = Uuid::parse('5f32efa0-2f13-4fcf-87d6-86c86a734247');

        static::assertFalse($uuid->equals('5f32efa0-2f13-4fcf-87d6-86c86a734247'));
    }

    public function test_that_equals_returns_false_for_unequal_values()
    {
        $uuid1 = Uuid::parse('5f32efa0-2f13-4fcf-87d6-86c86a734247');
        $uuid2 = Uuid::parse('5f32efa0-2f12-4fcf-87d6-86c86a734247');

        static::assertFalse($uuid1->equals($uuid2));
    }

    public function test_that_hash_value_returns_expected_string()
    {
        $uuid = Uuid::parse('5f32efa0-2f13-4fcf-87d6-86c86a734247');

        static::assertSame('5f32efa02f134fcf87d686c86a734247', $uuid->hashValue());
    }

    public function test_that_parse_throws_exception_with_invalid_string()
    {
        $this->expectException(DomainException::class);

        Uuid::parse('71967621-a924-4c58-a8f2e0-73967744e0');
    }

    public function test_that_from_hex_throws_exception_with_invalid_string()
    {
        $this->expectException(DomainException::class);

        Uuid::fromHex('71967621a9244c58a8f2e073967744e');
    }

    public function test_that_from_bytes_throws_exception_with_invalid_string()
    {
        $this->expectException(DomainException::class);

        $bytes = base64_decode('T2j8yJudgM8LNIVSRsUpW08=');
        Uuid::fromBytes($bytes);
    }

    public function test_that_time_throws_exception_with_invalid_timestamp()
    {
        $this->expectException(DomainException::class);

        $timestamp = '01E5152019E2b2620';
        $node = '48:2C:6A:1E:59:3D';
        $clockSeq = 0x398B;
        Uuid::time($node, $clockSeq, $timestamp);
    }

    public function test_that_time_throws_exception_with_invalid_node()
    {
        $this->expectException(DomainException::class);

        $timestamp = '01E5152019E2b262';
        $node = '48:2C:6A:1E:59:3D:1F';
        $clockSeq = 0x398B;
        Uuid::time($node, $clockSeq, $timestamp);
    }

    public function test_that_time_throws_exception_with_invalid_clock_seq()
    {
        $this->expectException(DomainException::class);

        $timestamp = '01E5152019E2b262';
        $node = '48:2C:6A:1E:59:3D';
        $clockSeq = 0xFA00;
        Uuid::time($node, $clockSeq, $timestamp);
    }

    public function test_that_compare_to_throws_exception_for_invalid_argument()
    {
        $this->expectException(AssertionException::class);

        $uuid = Uuid::random();
        $uuid->compareTo('5f32efa0-2f13-4fcf-87d6-86c86a734247');
    }
}
