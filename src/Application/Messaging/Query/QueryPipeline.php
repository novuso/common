<?php declare(strict_types = 1);

namespace Novuso\Common\Application\Messaging\Query;

use Novuso\Common\Domain\Messaging\Query\Query;
use Novuso\Common\Domain\Messaging\Query\QueryBus;
use Novuso\Common\Domain\Messaging\Query\QueryFilter;
use Novuso\Common\Domain\Messaging\Query\QueryMessage;
use Novuso\System\Collection\LinkedStack;

/**
 * QueryPipeline is the entry point for queries to the application
 *
 * @copyright Copyright (c) 2016, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class QueryPipeline implements QueryBus, QueryFilter
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
     * @var LinkedStack
     */
    protected $filters;

    /**
     * Filter stack
     *
     * @var LinkedStack|null
     */
    protected $stack;

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
    public function addFilter(QueryFilter $filter)
    {
        $this->filters->push($filter);
    }

    /**
     * {@inheritdoc}
     */
    public function fetch(Query $query)
    {
        $this->stack = clone $this->filters;
        $this->pipe(QueryMessage::create($query));

        $results = $this->results;
        $this->results = null;

        return $results;
    }

    /**
     * {@inheritdoc}
     */
    public function process(QueryMessage $message, callable $next)
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
     */
    public function pipe(QueryMessage $message)
    {
        /** @var QueryFilter $filter */
        $filter = $this->stack->pop();
        $filter->process($message, [$this, 'pipe']);
    }
}
