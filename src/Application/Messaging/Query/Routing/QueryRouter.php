<?php declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Query\Routing;

use Novuso\Common\Domain\Messaging\Query\QueryHandlerInterface;
use Novuso\Common\Domain\Messaging\Query\QueryInterface;

/**
 * QueryRouter matches queries from a query map
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class QueryRouter implements QueryRouterInterface
{
    /**
     * Query map
     *
     * @var QueryMapInterface
     */
    protected $queryMap;

    /**
     * Constructs QueryRouter
     *
     * @param QueryMapInterface $queryMap The query map
     */
    public function __construct(QueryMapInterface $queryMap)
    {
        $this->queryMap = $queryMap;
    }

    /**
     * {@inheritdoc}
     */
    public function match(QueryInterface $query): QueryHandlerInterface
    {
        return $this->queryMap->getHandler(get_class($query));
    }
}
