<?php declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Command;

use Novuso\Common\Domain\Messaging\Command\Command;
use Novuso\Common\Domain\Messaging\Command\CommandBus;
use Novuso\Common\Domain\Messaging\Command\CommandFilter;
use Novuso\Common\Domain\Messaging\Command\CommandMessage;
use Novuso\Common\Domain\Messaging\Command\SynchronousCommandBus;
use Novuso\System\Collection\LinkedStack;
use Novuso\System\Collection\Type\Stack;
use Throwable;

/**
 * Class CommandPipeline
 */
final class CommandPipeline implements SynchronousCommandBus, CommandFilter
{
    /**
     * Command bus
     *
     * @var CommandBus
     */
    protected $commandBus;

    /**
     * Command filters
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
     * Constructs CommandPipeline
     *
     * @param CommandBus $commandBus The command bus
     */
    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
        $this->filters = LinkedStack::of(CommandFilter::class);
        $this->filters->push($this);
    }

    /**
     * Adds a command filter to the pipeline
     *
     * @param CommandFilter $filter The filter
     *
     * @return void
     */
    public function addFilter(CommandFilter $filter): void
    {
        $this->filters->push($filter);
    }

    /**
     * {@inheritdoc}
     */
    public function execute(Command $command): void
    {
        $this->dispatch(CommandMessage::create($command));
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(CommandMessage $message): void
    {
        $this->executionStack = clone $this->filters;
        $this->pipe($message);
    }

    /**
     * {@inheritdoc}
     */
    public function process(CommandMessage $message, callable $next): void
    {
        $this->commandBus->dispatch($message);
    }

    /**
     * Pipes command message to the next filter
     *
     * @param CommandMessage $message The command message
     *
     * @return void
     *
     * @throws Throwable
     */
    public function pipe(CommandMessage $message): void
    {
        /** @var CommandFilter $filter */
        $filter = $this->executionStack->pop();
        $filter->process($message, [$this, 'pipe']);
    }
}
