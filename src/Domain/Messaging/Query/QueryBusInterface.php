<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Messaging\Query;

use Throwable;

/**
 * QueryBusInterface is the interface for a query bus
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface QueryBusInterface
{
    /**
     * Fetches query results
     *
     * @param QueryInterface $query The query
     *
     * @return mixed
     *
     * @throws Throwable When an error occurs
     */
    public function fetch(QueryInterface $query);

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
