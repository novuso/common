<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Repository;

use JsonSerializable;
use Novuso\System\Collection\Contract\Collection;
use Novuso\System\Collection\Mixin\ItemTypeMethods;
use Novuso\System\Collection\Type\Sequence;
use Novuso\System\Type\Arrayable;
use Traversable;

/**
 * Class ResultSet
 */
final class ResultSet implements Arrayable, Collection, JsonSerializable
{
    use ItemTypeMethods;

    /**
     * Page number
     *
     * @var int
     */
    protected $page;

    /**
     * Number of items per page
     *
     * @var int
     */
    protected $perPage;

    /**
     * Total number of pages
     *
     * @var int
     */
    protected $totalPages;

    /**
     * Total number of records
     *
     * @var int
     */
    protected $totalRecords;

    /**
     * Records
     *
     * @var Sequence
     */
    protected $records;

    /**
     * Constructs ResultSet
     *
     * @param int      $page         The page
     * @param int      $perPage      The number of items per page
     * @param int      $totalRecords The total number of records
     * @param Sequence $records      The records
     */
    public function __construct(int $page, int $perPage, int $totalRecords, Sequence $records)
    {
        $this->setItemType($records->itemType());
        $this->page = $page;
        $this->perPage = $perPage;
        $this->totalPages = $this->countPages($totalRecords, $perPage);
        $this->totalRecords = $totalRecords;
        $this->records = $records;
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty(): bool
    {
        return $this->records->isEmpty();
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return $this->records->count();
    }

    /**
     * Retrieves the page number
     *
     * @return int
     */
    public function page(): int
    {
        return $this->page;
    }

    /**
     * Retrieves the number of items per page
     *
     * @return int
     */
    public function perPage(): int
    {
        return $this->perPage;
    }

    /**
     * Retrieves the number of total pages
     *
     * @return int
     */
    public function totalPages(): int
    {
        return $this->totalPages;
    }

    /**
     * Retrieves the number of total records
     *
     * @return int
     */
    public function totalRecords(): int
    {
        return $this->totalRecords;
    }

    /**
     * Retrieves the records
     *
     * @return Sequence
     */
    public function records(): Sequence
    {
        return $this->records;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator(): Traversable
    {
        return $this->records->getIterator();
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        return [
            'page'          => $this->page,
            'per_page'      => $this->perPage,
            'total_pages'   => $this->totalPages,
            'total_records' => $this->totalRecords,
            'records'       => $this->records
        ];
    }

    /**
     * Retrieves a representation for JSON encoding
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Calculates the number of pages
     *
     * @param int $totalRecords The total records
     * @param int $perPage      The records per page
     *
     * @return int
     */
    protected function countPages(int $totalRecords, int $perPage): int
    {
        if ($totalRecords < 1 || $perPage < 1) {
            return 1;
        }

        return intval((($totalRecords - 1) / $perPage) + 1);
    }
}
