<?php declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Query\Routing;

use Novuso\Common\Domain\Messaging\Query\QueryHandler;
use Novuso\Common\Domain\Messaging\Query\Query;

/**
 * SimpleQueryRouter matches queries from a query map
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class SimpleQueryRouter implements QueryRouter
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
