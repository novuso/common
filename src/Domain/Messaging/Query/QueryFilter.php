<?php declare(strict_types=1);

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
     * function (QueryMessage $message): void {}
     * </code>
     *
     * @param QueryMessage $message The query message
     * @param callable     $next    The next filter
     *
     * @return void
     *
     * @throws Throwable When an error occurs
     */
    public function process(QueryMessage $message, callable $next): void;
}
