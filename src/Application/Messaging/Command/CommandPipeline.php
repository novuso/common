<?php declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Command;

use Novuso\Common\Domain\Messaging\Command\CommandBusInterface;
use Novuso\Common\Domain\Messaging\Command\CommandFilterInterface;
use Novuso\Common\Domain\Messaging\Command\CommandInterface;
use Novuso\Common\Domain\Messaging\Command\CommandMessage;
use Novuso\System\Collection\Api\StackInterface;
use Novuso\System\Collection\LinkedStack;

/**
 * CommandPipeline is the entry point for commands to the application
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class CommandPipeline implements CommandBusInterface, CommandFilterInterface
{
    /**
     * Command bus
     *
     * @var CommandBusInterface
     */
    protected $commandBus;

    /**
     * Command filters
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
     * Constructs CommandPipeline
     *
     * @param CommandBusInterface $commandBus The command bus
     */
    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
        $this->filters = LinkedStack::of(CommandFilterInterface::class);
        $this->filters->push($this);
    }

    /**
     * Adds a command filter to the pipeline
     *
     * @param CommandFilterInterface $filter The filter
     *
     * @return void
     */
    public function addFilter(CommandFilterInterface $filter): void
    {
        $this->filters->push($filter);
    }

    /**
     * {@inheritdoc}
     */
    public function execute(CommandInterface $command): void
    {
        $this->dispatch(CommandMessage::create($command));
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(CommandMessage $message): void
    {
        $this->stack = clone $this->filters;
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
     */
    public function pipe(CommandMessage $message): void
    {
        /** @var CommandFilterInterface $filter */
        $filter = $this->stack->pop();
        $filter->process($message, [$this, 'pipe']);
    }
}
