<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Query\Routing;

use Novuso\Common\Domain\Messaging\Query\Query;
use Novuso\Common\Domain\Messaging\Query\QueryHandler;

/**
 * Class SimpleQueryRouter
 */
final class SimpleQueryRouter implements QueryRouter
{
    /**
     * Constructs SimpleQueryRouter
     */
    public function __construct(protected QueryMap $queryMap)
    {
    }

    /**
     * @inheritDoc
     */
    public function match(Query $query): QueryHandler
    {
        return $this->queryMap->getHandler(get_class($query));
    }
}
