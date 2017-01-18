<?php declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Command\Routing;

use Novuso\Common\Application\Service\Container;
use Novuso\Common\Domain\Messaging\Command\Command;
use Novuso\Common\Domain\Messaging\Command\CommandHandler;
use Novuso\System\Exception\DomainException;
use Novuso\System\Exception\LookupException;
use Novuso\System\Type\Type;
use Novuso\System\Utility\Validate;

/**
 * ServiceAwareCommandMap is a command class to handler service map
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class ServiceAwareCommandMap
{
    /**
     * Service container
     *
     * @var Container
     */
    protected $container;

    /**
     * Command handlers
     *
     * @var array
     */
    protected $handlers = [];

    /**
     * Constructs ServiceAwareCommandMap
     *
     * @param Container $container The service container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Registers command handlers
     *
     * The command to handler map must follow this format:
     * [
     *     SomeCommand::class => 'handler_service_name'
     * ]
     *
     * @param array $commandToHandlerMap A map of class names to service names
     *
     * @return void
     *
     * @throws DomainException When a command class is not valid
     */
    public function registerHandlers(array $commandToHandlerMap)
    {
        foreach ($commandToHandlerMap as $commandClass => $serviceName) {
            $this->registerHandler($commandClass, $serviceName);
        }
    }

    /**
     * Registers a command handler
     *
     * @param string $commandClass The full command class name
     * @param string $serviceName  The handler service name
     *
     * @return void
     *
     * @throws DomainException When the command class is not valid
     */
    public function registerHandler(string $commandClass, string $serviceName)
    {
        if (!Validate::implementsInterface($commandClass, Command::class)) {
            $message = sprintf('Invalid command class: %s', $commandClass);
            throw new DomainException($message);
        }

        $type = Type::create($commandClass)->toString();

        $this->handlers[$type] = $serviceName;
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
        if (!$this->hasHandler($commandClass)) {
            $message = sprintf('Handler not defined for command: %s', $commandClass);
            throw new LookupException($message);
        }

        $type = Type::create($commandClass)->toString();
        $service = $this->handlers[$type];

        return $this->container->get($service);
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

        if (!isset($this->handlers[$type])) {
            return false;
        }

        $service = $this->handlers[$type];

        return $this->container->has($service);
    }
}
