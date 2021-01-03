<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Command\Routing;

use Novuso\Common\Domain\Messaging\Command\Command;
use Novuso\Common\Domain\Messaging\Command\CommandHandler;
use Novuso\System\Exception\LookupException;
use Novuso\System\Type\Type;
use Novuso\System\Utility\Assert;
use Psr\Container\ContainerInterface;

/**
 * Class ServiceAwareCommandMap
 */
final class ServiceAwareCommandMap implements CommandMap
{
    protected array $handlers = [];

    /**
     * Constructs ServiceAwareCommandMap
     */
    public function __construct(protected ContainerInterface $container)
    {
    }

    /**
     * Registers command handlers
     *
     * The command to handler map must follow this format:
     * [
     *     SomeCommand::class => 'handler_service_name'
     * ]
     */
    public function registerHandlers(array $commandToHandlerMap): void
    {
        foreach ($commandToHandlerMap as $commandClass => $serviceName) {
            $this->registerHandler($commandClass, $serviceName);
        }
    }

    /**
     * Registers a command handler
     */
    public function registerHandler(
        string $commandClass,
        string $serviceName
    ): void {
        Assert::implementsInterface($commandClass, Command::class);

        $type = Type::create($commandClass)->toString();

        $this->handlers[$type] = $serviceName;
    }

    /**
     * {@inheritdoc}
     */
    public function getHandler(string $commandClass): CommandHandler
    {
        if (!$this->hasHandler($commandClass)) {
            $message = sprintf(
                'Handler not defined for command: %s',
                $commandClass
            );
            throw new LookupException($message);
        }

        $type = Type::create($commandClass)->toString();
        $service = $this->handlers[$type];

        return $this->container->get($service);
    }

    /**
     * {@inheritdoc}
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
