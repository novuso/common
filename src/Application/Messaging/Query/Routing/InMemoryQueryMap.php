<?php declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Query\Routing;

use Novuso\Common\Domain\Messaging\Query\Query;
use Novuso\Common\Domain\Messaging\Query\QueryHandler;
use Novuso\System\Exception\AssertionException;
use Novuso\System\Exception\LookupException;
use Novuso\System\Type\Type;
use Novuso\System\Utility\Assert;

/**
 * Class InMemoryQueryMap
 */
final class InMemoryQueryMap implements QueryMap
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
     * @throws AssertionException When a query class is not valid
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
     * @throws AssertionException When the query class is not valid
     */
    public function registerHandler(string $queryClass, QueryHandler $handler): void
    {
        Assert::implementsInterface($queryClass, Query::class);

        $type = Type::create($queryClass)->toString();

        $this->handlers[$type] = $handler;
    }

    /**
     * {@inheritdoc}
     */
    public function getHandler(string $queryClass): QueryHandler
    {
        $type = Type::create($queryClass)->toString();

        if (!isset($this->handlers[$type])) {
            $message = sprintf('Handler not defined for query: %s', $queryClass);
            throw new LookupException($message);
        }

        return $this->handlers[$type];
    }

    /**
     * {@inheritdoc}
     */
    public function hasHandler(string $queryClass): bool
    {
        $type = Type::create($queryClass)->toString();

        return isset($this->handlers[$type]);
    }
}
