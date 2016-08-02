<?php declare(strict_type=1);

namespace Novuso\Common\Domain\Messaging\Query;

use Exception;

/**
 * QueryFilter is the interface for a query filter
 *
 * @copyright Copyright (c) 2016, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
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
     * @throws Exception When an error occurs
     */
    public function process(QueryMessage $message, callable $next);
}
