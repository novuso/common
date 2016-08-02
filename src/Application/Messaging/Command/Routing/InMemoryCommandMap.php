<?php declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Command\Routing;

use Novuso\Common\Domain\Messaging\Command\Command;
use Novuso\Common\Domain\Messaging\Command\CommandHandler;
use Novuso\System\Exception\DomainException;
use Novuso\System\Exception\LookupException;
use Novuso\System\Type\Type;
use Novuso\System\Utility\Test;

/**
 * InMemoryCommandMap is a command class to handler instance map
 *
 * @copyright Copyright (c) 2016, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class InMemoryCommandMap
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
     * @throws DomainException When a command class is not valid
     */
    public function registerHandlers(array $commandToHandlerMap)
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
     * @throws DomainException When the command class is not valid
     */
    public function registerHandler(string $commandClass, CommandHandler $handler)
    {
        if (!Test::implementsInterface($commandClass, Command::class)) {
            $message = sprintf('Invalid command class: %s', $commandClass);
            throw new DomainException($message);
        }

        $type = Type::create($commandClass)->toString();

        $this->handlers[$type] = $handler;
    }

    /**
     * Retrieves handler by command class name
     *
     * @param string $commandClass The full command class name
     *
     * @return CommandHandler
     *
     * @throws LookupException When a handler is not registered
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
     * Checks if a handler is defined for a command
     *
     * @param string $commandClass The full command class name
     *
     * @return bool
     */
    public function hasHandler(string $commandClass): bool
    {
        $type = Type::create($commandClass)->toString();

        return isset($this->handlers[$type]);
    }
}
