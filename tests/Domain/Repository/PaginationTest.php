<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Domain\Repository;

use Novuso\Common\Domain\Repository\Pagination;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Domain\Repository\Pagination
 */
class PaginationTest extends UnitTestCase
{
    public function test_that_new_pagination_without_arguments_returns_default_values()
    {
        $pagination = new Pagination();

        static::assertTrue(
            $pagination->page() === Pagination::DEFAULT_PAGE
            && $pagination->perPage() === Pagination::DEFAULT_PER_PAGE
            && $pagination->offset() === 0
            && $pagination->limit() === Pagination::DEFAULT_PER_PAGE
            && empty($pagination->orderings())
        );
    }

    public function test_that_orderings_returns_expected_value()
    {
        $pagination = new Pagination(
            Pagination::DEFAULT_PAGE,
            Pagination::DEFAULT_PER_PAGE,
            ['foo' => 'desc']
        );

        static::assertSame(['foo' => 'DESC'], $pagination->orderings());
    }

    public function test_that_orderings_are_only_asc_or_descending()
    {
        $pagination = new Pagination(
            Pagination::DEFAULT_PAGE,
            Pagination::DEFAULT_PER_PAGE,
            ['foo' => 'bar']
        );

        static::assertSame(['foo' => 'ASC'], $pagination->orderings());
    }
}
