<?php declare(strict_type=1);

namespace Novuso\Common\Domain\Messaging\Query;

use Exception;

/**
 * QueryHandler is the interface for a query handler
 *
 * @copyright Copyright (c) 2016, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface QueryHandler
{
    /**
     * Handles a query
     *
     * @param Query $query The query
     *
     * @return mixed
     *
     * @throws Exception When an error occurs
     */
    public function handle(Query $query);
}
