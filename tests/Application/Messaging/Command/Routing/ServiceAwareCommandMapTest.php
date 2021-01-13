<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Application\Messaging\Command\Routing;

use Novuso\Common\Application\Messaging\Command\Routing\CommandMap;
use Novuso\Common\Application\Messaging\Command\Routing\CommandRouter;
use Novuso\Common\Application\Messaging\Command\Routing\ServiceAwareCommandMap;
use Novuso\Common\Application\Messaging\Command\Routing\SimpleCommandRouter;
use Novuso\Common\Application\Messaging\Command\RoutingCommandBus;
use Novuso\Common\Application\Service\ServiceContainer;
use Novuso\Common\Domain\Messaging\Command\CommandBus;
use Novuso\Common\Test\Resources\Domain\Messaging\Command\RegisterUserCommand;
use Novuso\Common\Test\Resources\Domain\Messaging\Command\RegisterUserHandler;
use Novuso\System\Exception\AssertionException;
use Novuso\System\Exception\LookupException;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Messaging\Command\Routing\ServiceAwareCommandMap
 */
class ServiceAwareCommandMapTest extends UnitTestCase
{
    /** @var ServiceContainer */
    protected $container;

    protected function setUp(): void
    {
        $this->container = new ServiceContainer();
        $this->defineServices();
    }

    public function test_that_it_matches_the_correct_handler_instance()
    {
        $command = new RegisterUserCommand();
        $command
            ->setFirstName('James')
            ->setMiddleName('D')
            ->setLastName('Smith')
            ->setEmail('jsmith@example.com')
            ->setPassword('secret');

        /** @var CommandBus $commandBus */
        $commandBus = $this->container->get(CommandBus::class);
        $commandBus->execute($command);

        /** @var RegisterUserHandler $handler */
        $handler = $this->container->get(RegisterUserHandler::class);

        static::assertTrue($handler->isHandled());
    }

    public function test_that_map_has_handler_returns_true_when_handler_registered()
    {
        /** @var CommandMap $commandMap */
        $commandMap = $this->container->get(CommandMap::class);

        static::assertTrue($commandMap->hasHandler(RegisterUserCommand::class));
    }

    public function test_that_map_has_handler_returns_false_when_handler_not_registered()
    {
        $this->container->setParameter('command.handlers', []);
        /** @var CommandMap $commandMap */
        $commandMap = $this->container->get(CommandMap::class);

        static::assertFalse($commandMap->hasHandler(RegisterUserCommand::class));
    }

    public function test_that_register_handler_throws_exception_when_command_class_is_invalid()
    {
        $this->expectException(AssertionException::class);

        /** @var ServiceAwareCommandMap $commandMap */
        $commandMap = $this->container->get(CommandMap::class);
        $commandMap->registerHandler('FooBar', RegisterUserHandler::class);
    }

    public function test_that_get_handler_throws_exception_when_handler_is_not_registered()
    {
        $this->expectException(LookupException::class);

        $this->container->setParameter('command.handlers', []);
        /** @var CommandMap $commandMap */
        $commandMap = $this->container->get(CommandMap::class);
        $commandMap->getHandler(RegisterUserCommand::class);
    }

    protected function defineServices()
    {
        $this->container->set(
            CommandBus::class,
            function (ServiceContainer $container) {
                return new RoutingCommandBus($container->get(CommandRouter::class));
            }
        );

        $this->container->set(
            CommandRouter::class,
            function (ServiceContainer $container) {
                return new SimpleCommandRouter($container->get(CommandMap::class));
            }
        );

        $this->container->set(
            CommandMap::class,
            function (ServiceContainer $container) {
                $commandMap = new ServiceAwareCommandMap($container);
                $commandMap->registerHandlers($container->getParameter('command.handlers'));

                return $commandMap;
            }
        );

        $this->container->set(
            RegisterUserHandler::class,
            function () {
                return new RegisterUserHandler();
            }
        );

        $this->container->setParameter(
            'command.handlers',
            [RegisterUserCommand::class => RegisterUserHandler::class]
        );
    }
}
