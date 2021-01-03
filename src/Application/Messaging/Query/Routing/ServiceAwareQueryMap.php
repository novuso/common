<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Query\Routing;

use Novuso\Common\Domain\Messaging\Query\Query;
use Novuso\Common\Domain\Messaging\Query\QueryHandler;
use Novuso\System\Exception\LookupException;
use Novuso\System\Type\Type;
use Novuso\System\Utility\Assert;
use Psr\Container\ContainerInterface;

/**
 * Class ServiceAwareQueryMap
 */
final class ServiceAwareQueryMap implements QueryMap
{
    protected array $handlers = [];

    /**
     * Constructs ServiceAwareQueryMap
     */
    public function __construct(protected ContainerInterface $container)
    {
    }

    /**
     * Registers query handlers
     *
     * The query to handler map must follow this format:
     * [
     *     SomeQuery::class => 'handler_service_name'
     * ]
     */
    public function registerHandlers(array $queryToHandlerMap): void
    {
        foreach ($queryToHandlerMap as $queryClass => $serviceName) {
            $this->registerHandler($queryClass, $serviceName);
        }
    }

    /**
     * Registers a query handler
     */
    public function registerHandler(
        string $queryClass,
        string $serviceName
    ): void {
        Assert::implementsInterface($queryClass, Query::class);

        $type = Type::create($queryClass)->toString();

        $this->handlers[$type] = $serviceName;
    }

    /**
     * @inheritDoc
     */
    public function getHandler(string $queryClass): QueryHandler
    {
        if (!$this->hasHandler($queryClass)) {
            $message = sprintf(
                'Handler not defined for query: %s',
                $queryClass
            );
            throw new LookupException($message);
        }

        $type = Type::create($queryClass)->toString();
        $service = $this->handlers[$type];

        return $this->container->get($service);
    }

    /**
     * @inheritDoc
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
