<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Messaging\Query;

use Throwable;

/**
 * QueryHandler is the interface for a query handler
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface QueryHandler
{
    /**
     * Retrieves query registration
     *
     * Returns the fully qualified class name for the query that this service
     * is meant to handle.
     *
     * @return string
     */
    public static function queryRegistration(): string;

    /**
     * Handles a query
     *
     * @param QueryMessage $message The query message
     *
     * @return mixed
     *
     * @throws Throwable When an error occurs
     */
    public function handle(QueryMessage $message);
}
