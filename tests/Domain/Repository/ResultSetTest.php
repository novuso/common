<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Domain\Repository;

use Novuso\Common\Domain\Repository\Pagination;
use Novuso\Common\Domain\Repository\ResultSet;
use Novuso\System\Collection\ArrayList;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Domain\Repository\ResultSet
 */
class ResultSetTest extends UnitTestCase
{
    public function test_that_empty_result_set_is_valid()
    {
        $resultSet = new ResultSet(
            Pagination::DEFAULT_PAGE,
            Pagination::DEFAULT_PER_PAGE,
            0,
            new ArrayList()
        );

        static::assertTrue(
            $resultSet->isEmpty()
            && count($resultSet) === 0
            && $resultSet->page() === Pagination::DEFAULT_PAGE
            && $resultSet->perPage() === Pagination::DEFAULT_PER_PAGE
            && $resultSet->totalRecords() === 0
            && $resultSet->totalPages() === 1
            && $resultSet->records()->isEmpty()
        );
    }

    public function test_that_it_is_iterable()
    {
        $records = new ArrayList();
        $records->add(1);
        $records->add(2);
        $records->add(3);

        $resultSet = new ResultSet(
            Pagination::DEFAULT_PAGE,
            Pagination::DEFAULT_PER_PAGE,
            3,
            $records
        );

        $count = 0;
        foreach ($resultSet as $record) {
            $count++;
        }

        static::assertSame(3, $count);
    }

    public function test_that_it_is_json_encodable()
    {
        $records = new ArrayList();
        $records->add(1);
        $records->add(2);
        $records->add(3);

        $resultSet = new ResultSet(
            Pagination::DEFAULT_PAGE,
            Pagination::DEFAULT_PER_PAGE,
            3,
            $records
        );

        $expected = '{"page":1,"per_page":100,"total_pages":1,"total_records":3,"records":[1,2,3]}';
        static::assertSame($expected, json_encode($resultSet, JSON_UNESCAPED_SLASHES));
    }
}
