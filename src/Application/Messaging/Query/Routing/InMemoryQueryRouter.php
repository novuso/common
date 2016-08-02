<?php declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Query\Routing;

use Novuso\Common\Domain\Messaging\Query\Query;
use Novuso\Common\Domain\Messaging\Query\QueryHandler;

/**
 * InMemoryQueryRouter matches queries from an in-memory map
 *
 * @copyright Copyright (c) 2016, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class InMemoryQueryRouter implements QueryRouter
{
    /**
     * Query map
     *
     * @var InMemoryQueryMap
     */
    protected $queryMap;

    /**
     * Constructs InMemoryQueryRouter
     *
     * @param InMemoryQueryMap $queryMap The query map
     */
    public function __construct(InMemoryQueryMap $queryMap)
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
