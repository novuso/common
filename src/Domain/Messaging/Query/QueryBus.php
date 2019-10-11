<?php declare(strict_types=1);

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
     * @param Query $query The query
     *
     * @return mixed
     *
     * @throws Throwable When an error occurs
     */
    public function fetch(Query $query);

    /**
     * Dispatches a command message
     *
     * @param QueryMessage $message The query message
     *
     * @return mixed
     *
     * @throws Throwable When an error occurs
     */
    public function dispatch(QueryMessage $message);
}
