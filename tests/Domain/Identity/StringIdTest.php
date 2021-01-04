<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Domain\Identity;

use Novuso\Common\Test\Resources\Domain\Identity\Isbn;
use Novuso\System\Exception\AssertionException;
use Novuso\System\Exception\DomainException;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Domain\Identity\StringId
 */
class StringIdTest extends UnitTestCase
{
    public function test_that_from_string_returns_expected_instance()
    {
        $isbn = Isbn::fromString('978-1491905012');
        static::assertSame('978-1491905012', $isbn->toString());
    }

    public function test_that_string_cast_returns_expected_value()
    {
        $isbn = Isbn::fromString('978-1491905012');
        static::assertSame('978-1491905012', (string) $isbn);
    }

    public function test_that_compare_to_returns_zero_for_same_instance()
    {
        $isbn = Isbn::fromString('978-1491905012');
        static::assertSame(0, $isbn->compareTo($isbn));
    }

    public function test_that_compare_to_returns_zero_for_same_value()
    {
        $isbn1 = Isbn::fromString('978-1491905012');
        $isbn2 = Isbn::fromString('978-1491905012');
        static::assertSame(0, $isbn1->compareTo($isbn2));
    }

    public function test_that_compare_to_returns_one_for_greater_value()
    {
        $isbn1 = Isbn::fromString('978-1491905012');
        $isbn2 = Isbn::fromString('978-1449363758');
        static::assertSame(1, $isbn1->compareTo($isbn2));
    }

    public function test_that_compare_to_returns_neg_one_for_lesser_value()
    {
        $isbn1 = Isbn::fromString('978-1449363758');
        $isbn2 = Isbn::fromString('978-1491905012');
        static::assertSame(-1, $isbn1->compareTo($isbn2));
    }

    public function test_that_equals_returns_true_for_same_instance()
    {
        $isbn = Isbn::fromString('978-1491905012');
        static::assertTrue($isbn->equals($isbn));
    }

    public function test_that_equals_returns_true_for_same_value()
    {
        $isbn1 = Isbn::fromString('978-1491905012');
        $isbn2 = Isbn::fromString('978-1491905012');
        static::assertTrue($isbn1->equals($isbn2));
    }

    public function test_that_equals_returns_false_for_invalid_type()
    {
        $isbn = Isbn::fromString('978-1491905012');
        static::assertFalse($isbn->equals('978-1491905012'));
    }

    public function test_that_equals_returns_false_for_unequal_value()
    {
        $isbn1 = Isbn::fromString('978-1491905012');
        $isbn2 = Isbn::fromString('978-1449363758');
        static::assertFalse($isbn1->equals($isbn2));
    }

    public function test_that_hash_value_returns_expected_string()
    {
        $isbn = Isbn::fromString('978-1491905012');
        static::assertSame('978-1491905012', $isbn->hashValue());
    }

    public function test_that_guard_id_is_called_from_constructor_for_validation()
    {
        $this->expectException(DomainException::class);

        Isbn::fromString('9781491905012');
    }

    public function test_that_compare_to_throws_exception_for_invalid_argument()
    {
        $this->expectException(AssertionException::class);

        $isbn = Isbn::fromString('978-1491905012');
        $isbn->compareTo('978-1491905012');
    }
}
