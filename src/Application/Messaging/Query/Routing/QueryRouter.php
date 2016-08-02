<?php declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Query\Routing;

use Novuso\Common\Domain\Messaging\Query\Query;
use Novuso\Common\Domain\Messaging\Query\QueryHandler;
use Novuso\System\Exception\LookupException;

/**
 * QueryRouter matches a query to a handler
 *
 * @copyright Copyright (c) 2016, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface QueryRouter
{
    /**
     * Matches a query to a handler
     *
     * @param Query $query The query
     *
     * @return QueryHandler
     *
     * @throws LookupException When the handler is not found
     */
    public function match(Query $query): QueryHandler;
}
