<?php declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Query\Routing;

use Novuso\Common\Domain\Messaging\Query\QueryHandlerInterface;
use Novuso\Common\Domain\Messaging\Query\QueryInterface;
use Novuso\System\Exception\LookupException;

/**
 * QueryRouterInterface matches a query to a handler
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface QueryRouterInterface
{
    /**
     * Matches a query to a handler
     *
     * @param QueryInterface $query The query
     *
     * @return QueryHandlerInterface
     *
     * @throws LookupException When the handler is not found
     */
    public function match(QueryInterface $query): QueryHandlerInterface;
}
