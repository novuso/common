<?php declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Query;

use Novuso\Common\Domain\Messaging\Query\QueryBusInterface;
use Novuso\Common\Domain\Messaging\Query\QueryFilterInterface;
use Novuso\Common\Domain\Messaging\Query\QueryInterface;
use Novuso\Common\Domain\Messaging\Query\QueryMessage;
use Novuso\System\Collection\Api\StackInterface;
use Novuso\System\Collection\LinkedStack;

/**
 * QueryPipeline is the entry point for queries to the application
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class QueryPipeline implements QueryBusInterface, QueryFilterInterface
{
    /**
     * Query bus
     *
     * @var QueryBusInterface
     */
    protected $queryBus;

    /**
     * Query filters
     *
     * @var StackInterface
     */
    protected $filters;

    /**
     * Filter stack
     *
     * @var StackInterface|null
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
     * @param QueryBusInterface $queryBus The query bus
     */
    public function __construct(QueryBusInterface $queryBus)
    {
        $this->queryBus = $queryBus;
        $this->filters = LinkedStack::of(QueryFilterInterface::class);
        $this->filters->push($this);
    }

    /**
     * Adds a query filter to the pipeline
     *
     * @param QueryFilterInterface $filter The filter
     *
     * @return void
     */
    public function addFilter(QueryFilterInterface $filter): void
    {
        $this->filters->push($filter);
    }

    /**
     * {@inheritdoc}
     */
    public function fetch(QueryInterface $query)
    {
        return $this->dispatch(QueryMessage::create($query));
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(QueryMessage $message)
    {
        $this->stack = clone $this->filters;
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
        /** @var QueryInterface $query */
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
    public function pipe(QueryMessage $message): void
    {
        /** @var QueryFilterInterface $filter */
        $filter = $this->stack->pop();
        $filter->process($message, [$this, 'pipe']);
    }
}
