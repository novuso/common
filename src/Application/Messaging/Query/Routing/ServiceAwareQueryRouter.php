<?php declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Query\Routing;

use Novuso\Common\Domain\Messaging\Query\Query;
use Novuso\Common\Domain\Messaging\Query\QueryHandler;

/**
 * ServiceAwareQueryRouter matches queries from a service map
 *
 * @copyright Copyright (c) 2016, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class ServiceAwareQueryRouter implements QueryRouter
{
    /**
     * Query map
     *
     * @var ServiceAwareQueryMap
     */
    protected $queryMap;

    /**
     * Constructs ServiceAwareQueryRouter
     *
     * @param ServiceAwareQueryMap $queryMap The query map
     */
    public function __construct(ServiceAwareQueryMap $queryMap)
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
