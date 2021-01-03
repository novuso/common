<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Query\Routing;

use Novuso\Common\Domain\Messaging\Query\Query;
use Novuso\Common\Domain\Messaging\Query\QueryHandler;
use Novuso\System\Exception\LookupException;
use Novuso\System\Type\Type;
use Novuso\System\Utility\Assert;

/**
 * Class InMemoryQueryMap
 */
final class InMemoryQueryMap implements QueryMap
{
    protected array $handlers = [];

    /**
     * Registers query handlers
     *
     * The query to handler map must follow this format:
     * [
     *     SomeQuery::class => $someHandlerInstance
     * ]
     */
    public function registerHandlers(array $queryToHandlerMap): void
    {
        foreach ($queryToHandlerMap as $queryClass => $handler) {
            $this->registerHandler($queryClass, $handler);
        }
    }

    /**
     * Registers a query handler
     */
    public function registerHandler(
        string $queryClass,
        QueryHandler $handler
    ): void {
        Assert::implementsInterface($queryClass, Query::class);

        $type = Type::create($queryClass)->toString();

        $this->handlers[$type] = $handler;
    }

    /**
     * @inheritDoc
     */
    public function getHandler(string $queryClass): QueryHandler
    {
        $type = Type::create($queryClass)->toString();

        if (!isset($this->handlers[$type])) {
            $message = sprintf(
                'Handler not defined for query: %s',
                $queryClass
            );
            throw new LookupException($message);
        }

        return $this->handlers[$type];
    }

    /**
     * @inheritDoc
     */
    public function hasHandler(string $queryClass): bool
    {
        $type = Type::create($queryClass)->toString();

        return isset($this->handlers[$type]);
    }
}
