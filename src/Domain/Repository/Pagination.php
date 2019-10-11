<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Repository;

/**
 * Class Pagination
 */
final class Pagination
{
    /**
     * Ascending order
     *
     * @var string
     */
    public const ASC = 'ASC';

    /**
     * Descending order
     *
     * @var string
     */
    public const DESC = 'DESC';

    /**
     * Default page
     *
     * @var int
     */
    public const DEFAULT_PAGE = 1;

    /**
     * Default number of items per page
     *
     * @var int
     */
    public const DEFAULT_PER_PAGE = 100;

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
     * Offset
     *
     * @var int
     */
    protected $offset;

    /**
     * Limit
     *
     * @var int
     */
    protected $limit;

    /**
     * List of orderings
     *
     * @var array
     */
    protected $orderings;

    /**
     * Constructs Pagination
     *
     * @param int|null $page      The page or null for default
     * @param int|null $perPage   The number of items per page or null for default
     * @param array    $orderings Keys are field names; values are ordering ("asc" or "desc")
     */
    public function __construct(int $page = null, int $perPage = null, array $orderings = [])
    {
        $this->page = $page ?: static::DEFAULT_PAGE;
        $this->perPage = $perPage ?: static::DEFAULT_PER_PAGE;

        $this->offset = ($this->page - 1) * $this->perPage;
        $this->limit = $this->perPage;
        $this->orderings = array_map(function (string $ordering) {
            return strtoupper($ordering) === static::DESC ? static::DESC : static::ASC;
        }, $orderings);
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
     * Retrieves the offset
     *
     * @return int
     */
    public function offset(): int
    {
        return $this->offset;
    }

    /**
     * Retrieves the limit
     *
     * @return int
     */
    public function limit(): int
    {
        return $this->limit;
    }

    /**
     * Retrieves the orderings
     *
     * @return array
     */
    public function orderings(): array
    {
        return $this->orderings;
    }
}
