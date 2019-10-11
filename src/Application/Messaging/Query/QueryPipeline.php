<?php declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Query;

use Novuso\Common\Domain\Messaging\Query\Query;
use Novuso\Common\Domain\Messaging\Query\QueryBus;
use Novuso\Common\Domain\Messaging\Query\QueryFilter;
use Novuso\Common\Domain\Messaging\Query\QueryMessage;
use Novuso\System\Collection\LinkedStack;
use Novuso\System\Collection\Type\Stack;
use Throwable;

/**
 * Class QueryPipeline
 */
final class QueryPipeline implements QueryBus, QueryFilter
{
    /**
     * Query bus
     *
     * @var QueryBus
     */
    protected $queryBus;

    /**
     * Query filters
     *
     * @var Stack
     */
    protected $filters;

    /**
     * Filter stack
     *
     * @var Stack|null
     */
    protected $executionStack;

    /**
     * Query results
     *
     * @var mixed
     */
    protected $results;

    /**
     * Constructs QueryPipeline
     *
     * @param QueryBus $queryBus The query bus
     */
    public function __construct(QueryBus $queryBus)
    {
        $this->queryBus = $queryBus;
        $this->filters = LinkedStack::of(QueryFilter::class);
        $this->filters->push($this);
    }

    /**
     * Adds a query filter to the pipeline
     *
     * @param QueryFilter $filter The filter
     *
     * @return void
     */
    public function addFilter(QueryFilter $filter): void
    {
        $this->filters->push($filter);
    }

    /**
     * {@inheritdoc}
     */
    public function fetch(Query $query)
    {
        return $this->dispatch(QueryMessage::create($query));
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(QueryMessage $message)
    {
        $this->executionStack = clone $this->filters;
        $this->pipe($message);

        $results = $this->results;
        $this->results = null;

        return $results;
    }

    /**
     * {@inheritdoc}
     */
    public function process(QueryMessage $message, callable $next): void
    {
        /** @var Query $query */
        $query = $message->payload();
        $this->results = $this->queryBus->fetch($query);
    }

    /**
     * Pipes query message to the next filter
     *
     * @param QueryMessage $message The query message
     *
     * @return void
     *
     * @throws Throwable
     */
    public function pipe(QueryMessage $message): void
    {
        /** @var QueryFilter $filter */
        $filter = $this->executionStack->pop();
        $filter->process($message, [$this, 'pipe']);
    }
}
