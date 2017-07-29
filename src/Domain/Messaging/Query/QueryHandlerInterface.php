<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Messaging\Query;

use Throwable;

/**
 * QueryHandlerInterface is the interface for a query handler
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface QueryHandlerInterface
{
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