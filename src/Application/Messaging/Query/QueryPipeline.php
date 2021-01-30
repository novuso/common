<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Query;

use Novuso\Common\Domain\Messaging\Query\Query;
use Novuso\Common\Domain\Messaging\Query\QueryBus;
use Novuso\Common\Domain\Messaging\Query\QueryFilter;
use Novuso\Common\Domain\Messaging\Query\QueryMessage;
use Novuso\System\Collection\LinkedStack;
use Throwable;

/**
 * Class QueryPipeline
 */
final class QueryPipeline implements QueryBus, QueryFilter
{
    protected LinkedStack $filters;
    protected ?LinkedStack $executionStack = null;
    protected mixed $results;

    /**
     * Constructs QueryPipeline
     */
    public function __construct(protected QueryBus $queryBus)
    {
        $this->filters = LinkedStack::of(QueryFilter::class);
        $this->filters->push($this);
    }

    /**
     * Adds a query filter to the pipeline
     */
    public function addFilter(QueryFilter $filter): void
    {
        $this->filters->push($filter);
    }

    /**
     * @inheritDoc
     */
    public function fetch(Query $query): mixed
    {
        return $this->dispatch(QueryMessage::create($query));
    }

    /**
     * @inheritDoc
     */
    public function dispatch(QueryMessage $queryMessage): mixed
    {
        $this->executionStack = clone $this->filters;
        $this->pipe($queryMessage);

        $results = $this->results;
        $this->results = null;

        return $results;
    }

    /**
     * @inheritDoc
     */
    public function process(QueryMessage $queryMessage, callable $next): void
    {
        /** @var Query $query */
        $query = $queryMessage->payload();
        $this->results = $this->queryBus->fetch($query);
    }

    /**
     * Pipes query message to the next filter
     *
     * @throws Throwable
     */
    public function pipe(QueryMessage $queryMessage): void
    {
        /** @var QueryFilter $filter */
        $filter = $this->executionStack->pop();
        $filter->process($queryMessage, [$this, 'pipe']);
    }
}
