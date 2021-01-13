<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Query\Routing;

use Novuso\Common\Domain\Messaging\Query\QueryHandler;
use Novuso\System\Exception\LookupException;

/**
 * Interface QueryMap
 */
interface QueryMap
{
    /**
     * Retrieves handler by query class name
     *
     * @throws LookupException When a handler is not registered
     */
    public function getHandler(string $queryClass): QueryHandler;

    /**
     * Checks if a handler is defined for a query
     */
    public function hasHandler(string $queryClass): bool;
}
