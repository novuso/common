<?php

declare(strict_types=1);

namespace Novuso\Common\Domain\Messaging\Query;

use Throwable;

/**
 * Interface QueryBus
 */
interface QueryBus
{
    /**
     * Fetches query results
     *
     * @throws Throwable When an error occurs
     */
    public function fetch(Query $query): mixed;

    /**
     * Dispatches a query message
     *
     * @throws Throwable When an error occurs
     */
    public function dispatch(QueryMessage $queryMessage): mixed;
}
