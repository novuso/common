<?php declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Query\Routing;

use Novuso\Common\Domain\Messaging\Query\Query;
use Novuso\Common\Domain\Messaging\Query\QueryHandler;
use Novuso\System\Exception\DomainException;
use Novuso\System\Exception\LookupException;
use Novuso\System\Utility\Validate;
use function Novuso\Common\Functions\type;

/**
 * InMemoryQueryMap is a query class to handler instance map
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class InMemoryQueryMap
{
    /**
     * Query handlers
     *
     * @var array
     */
    protected $handlers = [];

    /**
     * Registers query handlers
     *
     * The query to handler map must follow this format:
     * [
     *     SomeQuery::class => $someHandlerInstance
     * ]
     *
     * @param array $queryToHandlerMap A map of class names to handlers
     *
     * @return void
     *
     * @throws DomainException When a query class is not valid
     */
    public function registerHandlers(array $queryToHandlerMap): void
    {
        foreach ($queryToHandlerMap as $queryClass => $handler) {
            $this->registerHandler($queryClass, $handler);
        }
    }

    /**
     * Registers a query handler
     *
     * @param string       $queryClass The full query class name
     * @param QueryHandler $handler    The query handler
     *
     * @return void
     *
     * @throws DomainException When the query class is not valid
     */
    public function registerHandler(string $queryClass, QueryHandler $handler): void
    {
        if (!Validate::implementsInterface($queryClass, Query::class)) {
            $message = sprintf('Invalid query class: %s', $queryClass);
            throw new DomainException($message);
        }

        $type = type($queryClass)->toString();

        $this->handlers[$type] = $handler;
    }

    /**
     * Retrieves handler by query class name
     *
     * @param string $queryClass The full query class name
     *
     * @return QueryHandler
     *
     * @throws LookupException When a handler is not registered
     */
    public function getHandler(string $queryClass): QueryHandler
    {
        $type = type($queryClass)->toString();

        if (!isset($this->handlers[$type])) {
            $message = sprintf('Handler not defined for query: %s', $queryClass);
            throw new LookupException($message);
        }

        return $this->handlers[$type];
    }

    /**
     * Checks if a handler is defined for a query
     *
     * @param string $queryClass The full query class name
     *
     * @return bool
     */
    public function hasHandler(string $queryClass): bool
    {
        $type = type($queryClass)->toString();

        return isset($this->handlers[$type]);
    }
}
