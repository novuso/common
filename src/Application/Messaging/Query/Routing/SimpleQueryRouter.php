<?php declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Query\Routing;

use Novuso\Common\Domain\Messaging\Query\Query;
use Novuso\Common\Domain\Messaging\Query\QueryHandler;

/**
 * Class SimpleQueryRouter
 */
final class SimpleQueryRouter implements QueryRouter
{
    /**
     * Query map
     *
     * @var QueryMap
     */
    protected $queryMap;

    /**
     * Constructs SimpleQueryRouter
     *
     * @param QueryMap $queryMap The query map
     */
    public function __construct(QueryMap $queryMap)
    {
        $this->queryMap = $queryMap;
    }

    /**
     * {@inheritdoc}
     */
    public function match(Query $query): QueryHandler
    {
        return $this->queryMap->getHandler(get_class($query));
    }
}
