<?php

declare(strict_types=1);

namespace Novuso\Common\Domain\Messaging\Query;

use Throwable;

/**
 * Interface QueryFilter
 */
interface QueryFilter
{
    /**
     * Processes a query message and calls the next filter
     *
     * Signature of $next:
     *
     * <code>
     * function (QueryMessage $queryMessage): void {}
     * </code>
     *
     * @throws Throwable When an error occurs
     */
    public function process(QueryMessage $queryMessage, callable $next): void;
}
