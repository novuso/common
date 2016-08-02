<?php declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Query\Routing;

use Novuso\Common\Application\Service\Container;
use Novuso\Common\Domain\Messaging\Query\Query;
use Novuso\Common\Domain\Messaging\Query\QueryHandler;
use Novuso\System\Exception\DomainException;
use Novuso\System\Exception\LookupException;
use Novuso\System\Type\Type;
use Novuso\System\Utility\Test;

/**
 * ServiceAwareQueryMap is a query class to handler service map
 *
 * @copyright Copyright (c) 2016, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class ServiceAwareQueryMap
{
    /**
     * Service container
     *
     * @var Container
     */
    protected $container;

    /**
     * Query handlers
     *
     * @var array
     */
    protected $handlers = [];

    /**
     * Constructs ServiceAwareQueryMap
     *
     * @param Container $container The service container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Registers query handlers
     *
     * The query to handler map must follow this format:
     * [
     *     SomeQuery::class => 'handler_service_name'
     * ]
     *
     * @param array $queryToHandlerMap A map of class names to service names
     *
     * @return void
     *
     * @throws DomainException When a query class is not valid
     */
    public function registerHandlers(array $queryToHandlerMap)
    {
        foreach ($queryToHandlerMap as $queryClass => $serviceName) {
            $this->registerHandler($queryClass, $serviceName);
        }
    }

    /**
     * Registers a query handler
     *
     * @param string $queryClass  The full query class name
     * @param string $serviceName The handler service name
     *
     * @return void
     *
     * @throws DomainException When the query class is not valid
     */
    public function registerHandler(string $queryClass, string $serviceName)
    {
        if (!Test::implementsInterface($queryClass, Query::class)) {
            $message = sprintf('Invalid query class: %s', $queryClass);
            throw new DomainException($message);
        }

        $type = Type::create($queryClass)->toString();

        $this->handlers[$type] = $serviceName;
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
        if (!$this->hasHandler($queryClass)) {
            $message = sprintf('Handler not defined for query: %s', $queryClass);
            throw new LookupException($message);
        }

        $type = Type::create($queryClass)->toString();
        $service = $this->handlers[$type];

        return $this->container->get($service);
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
        $type = Type::create($queryClass)->toString();

        if (!isset($this->handlers[$type])) {
            return false;
        }

        $service = $this->handlers[$type];

        return $this->container->has($service);
    }
}
