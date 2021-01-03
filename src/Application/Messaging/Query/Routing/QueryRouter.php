<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Query\Routing;

use Novuso\Common\Domain\Messaging\Query\Query;
use Novuso\Common\Domain\Messaging\Query\QueryHandler;
use Novuso\System\Exception\LookupException;

/**
 * Interface QueryRouter
 */
interface QueryRouter
{
    /**
     * Matches a query to a handler
     *
     * @throws LookupException When the handler is not found
     */
    public function match(Query $query): QueryHandler;
}
