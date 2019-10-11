<?php declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Query\Routing;

use Novuso\Common\Domain\Messaging\Query\Query;
use Novuso\Common\Domain\Messaging\Query\QueryHandler;
use Novuso\System\Exception\AssertionException;
use Novuso\System\Exception\LookupException;
use Novuso\System\Type\Type;
use Novuso\System\Utility\Assert;
use Psr\Container\ContainerInterface;

/**
 * Class ServiceAwareQueryMap
 */
final class ServiceAwareQueryMap implements QueryMap
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
     * @throws AssertionException When a query class is not valid
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
     * @throws AssertionException When the query class is not valid
     */
    public function registerHandler(string $queryClass, string $serviceName): void
    {
        Assert::implementsInterface($queryClass, Query::class);

        $type = Type::create($queryClass)->toString();

        $this->handlers[$type] = $serviceName;
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
