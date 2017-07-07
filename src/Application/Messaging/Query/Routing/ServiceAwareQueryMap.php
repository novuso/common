<?php declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Query\Routing;

use Novuso\Common\Domain\Messaging\Query\Query;
use Novuso\Common\Domain\Messaging\Query\QueryHandler;
use Novuso\System\Exception\DomainException;
use Novuso\System\Exception\LookupException;
use Novuso\System\Utility\Validate;
use Psr\Container\ContainerInterface;
use function Novuso\Common\Functions\type;

/**
 * ServiceAwareQueryMap is a query class to handler service map
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class ServiceAwareQueryMap
{
    /**
     * Service container
     *
     * @var ContainerInterface
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
     * @param ContainerInterface $container The service container
     */
    public function __construct(ContainerInterface $container)
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
    public function registerHandlers(array $queryToHandlerMap): void
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
    public function registerHandler(string $queryClass, string $serviceName): void
    {
        if (!Validate::implementsInterface($queryClass, Query::class)) {
            $message = sprintf('Invalid query class: %s', $queryClass);
            throw new DomainException($message);
        }

        $type = type($queryClass)->toString();

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

        $type = type($queryClass)->toString();
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
        $type = type($queryClass)->toString();

        if (!isset($this->handlers[$type])) {
            return false;
        }

        $service = $this->handlers[$type];

        return $this->container->has($service);
    }
}
