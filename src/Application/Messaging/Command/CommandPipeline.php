<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Command;

use Novuso\Common\Domain\Messaging\Command\Command;
use Novuso\Common\Domain\Messaging\Command\CommandBus;
use Novuso\Common\Domain\Messaging\Command\CommandFilter;
use Novuso\Common\Domain\Messaging\Command\CommandMessage;
use Novuso\Common\Domain\Messaging\Command\SynchronousCommandBus;
use Novuso\System\Collection\LinkedStack;
use Throwable;

/**
 * Class CommandPipeline
 */
final class CommandPipeline implements SynchronousCommandBus, CommandFilter
{
    protected LinkedStack $filters;
    protected ?LinkedStack $executionStack = null;

    /**
     * Constructs CommandPipeline
     */
    public function __construct(protected CommandBus $commandBus)
    {
        $this->filters = LinkedStack::of(CommandFilter::class);
        $this->filters->push($this);
    }

    /**
     * Adds a command filter to the pipeline
     */
    public function addFilter(CommandFilter $filter): void
    {
        $this->filters->push($filter);
    }

    /**
     * @inheritDoc
     */
    public function execute(Command $command): void
    {
        $this->dispatch(CommandMessage::create($command));
    }

    /**
     * @inheritDoc
     */
    public function dispatch(CommandMessage $commandMessage): void
    {
        $this->executionStack = clone $this->filters;
        $this->pipe($commandMessage);
    }

    /**
     * @inheritDoc
     */
    public function process(CommandMessage $commandMessage, callable $next): void
    {
        $this->commandBus->dispatch($commandMessage);
    }

    /**
     * Pipes command message to the next filter
     *
     * @throws Throwable
     */
    public function pipe(CommandMessage $commandMessage): void
    {
        /** @var CommandFilter $filter */
        $filter = $this->executionStack->pop();
        $filter->process($commandMessage, [$this, 'pipe']);
    }
}
