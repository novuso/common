<?php declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Command;

use Novuso\Common\Domain\Messaging\Command\Command;
use Novuso\Common\Domain\Messaging\Command\CommandBus;
use Novuso\Common\Domain\Messaging\Command\CommandFilter;
use Novuso\Common\Domain\Messaging\Command\CommandMessage;
use Novuso\System\Collection\LinkedStack;

/**
 * CommandPipeline is the entry point for commands to the application
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class CommandPipeline implements CommandBus, CommandFilter
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
    public function addFilter(CommandFilter $filter)
    {
        $this->filters->push($filter);
    }

    /**
     * {@inheritdoc}
     */
    public function execute(Command $command)
    {
        $this->stack = clone $this->filters;
        $this->pipe(CommandMessage::create($command));
    }

    /**
     * {@inheritdoc}
     */
    public function process(CommandMessage $message, callable $next)
    {
        /** @var Command $command */
        $command = $message->payload();
        $this->commandBus->execute($command);
    }

    /**
     * Pipes command message to the next filter
     *
     * @param CommandMessage $message The command message
     *
     * @return void
     */
    public function pipe(CommandMessage $message)
    {
        /** @var CommandFilter $filter */
        $filter = $this->stack->pop();
        $filter->process($message, [$this, 'pipe']);
    }
}
