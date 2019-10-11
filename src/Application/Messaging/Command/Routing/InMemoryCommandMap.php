<?php declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Command\Routing;

use Novuso\Common\Domain\Messaging\Command\Command;
use Novuso\Common\Domain\Messaging\Command\CommandHandler;
use Novuso\System\Exception\AssertionException;
use Novuso\System\Exception\LookupException;
use Novuso\System\Type\Type;
use Novuso\System\Utility\Assert;

/**
 * Class InMemoryCommandMap
 */
final class InMemoryCommandMap implements CommandMap
{
    /**
     * Command handlers
     *
     * @var array
     */
    protected $handlers = [];

    /**
     * Registers command handlers
     *
     * The command to handler map must follow this format:
     * [
     *     SomeCommand::class => $someHandlerInstance
     * ]
     *
     * @param array $commandToHandlerMap A map of class names to handlers
     *
     * @return void
     *
     * @throws AssertionException When a command class is not valid
     */
    public function registerHandlers(array $commandToHandlerMap): void
    {
        foreach ($commandToHandlerMap as $commandClass => $handler) {
            $this->registerHandler($commandClass, $handler);
        }
    }

    /**
     * Registers a command handler
     *
     * @param string         $commandClass The full command class name
     * @param CommandHandler $handler      The command handler
     *
     * @return void
     *
     * @throws AssertionException When the command class is not valid
     */
    public function registerHandler(string $commandClass, CommandHandler $handler): void
    {
        Assert::implementsInterface($commandClass, Command::class);

        $type = Type::create($commandClass)->toString();

        $this->handlers[$type] = $handler;
    }

    /**
     * {@inheritdoc}
     */
    public function getHandler(string $commandClass): CommandHandler
    {
        $type = Type::create($commandClass)->toString();

        if (!isset($this->handlers[$type])) {
            $message = sprintf('Handler not defined for command: %s', $commandClass);
            throw new LookupException($message);
        }

        return $this->handlers[$type];
    }

    /**
     * {@inheritdoc}
     */
    public function hasHandler(string $commandClass): bool
    {
        $type = Type::create($commandClass)->toString();

        return isset($this->handlers[$type]);
    }
}
