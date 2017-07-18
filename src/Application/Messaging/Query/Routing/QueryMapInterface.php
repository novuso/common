<?php declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Query\Routing;

use Novuso\Common\Domain\Messaging\Query\QueryHandlerInterface;
use Novuso\System\Exception\LookupException;

/**
 * QueryMapInterface is the interface for a query map
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface QueryMapInterface
{
    /**
     * Retrieves handler by query class name
     *
     * @param string $queryClass The full query class name
     *
     * @return QueryHandlerInterface
     *
     * @throws LookupException When a handler is not registered
     */
    public function getHandler(string $queryClass): QueryHandlerInterface;

    /**
     * Checks if a handler is defined for a query
     *
     * @param string $queryClass The full query class name
     *
     * @return bool
     */
    public function hasHandler(string $queryClass): bool;
}
