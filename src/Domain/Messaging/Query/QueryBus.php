<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Messaging\Query;

use Throwable;

/**
 * QueryBus is the interface for a query bus
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
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
