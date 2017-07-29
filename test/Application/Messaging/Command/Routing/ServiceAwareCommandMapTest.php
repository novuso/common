<?php

namespace Novuso\Test\Common\Application\Messaging\Command\Routing;

use Novuso\Common\Application\Messaging\Command\Routing\CommandRouter;
use Novuso\Common\Application\Messaging\Command\Routing\ServiceAwareCommandMap;
use Novuso\Common\Application\Messaging\Command\SynchronousCommandBus;
use Novuso\Common\Application\Service\ServiceContainer;
use Novuso\Common\Domain\Messaging\Command\CommandBusInterface;
use Novuso\Test\Common\Resources\Domain\Messaging\Command\RegisterUserCommand;
use Novuso\Test\Common\Resources\Domain\Messaging\Command\RegisterUserHandler;
use Novuso\Test\System\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Messaging\Command\Routing\ServiceAwareCommandMap
 */
class ServiceAwareCommandMapTest extends UnitTestCase
{
    /** @var ServiceContainer */
    protected $container;

    protected function setUp()
    {
        parent::setUp();
        $this->container = new ServiceContainer();
        $this->container['command.handlers'] = [
            RegisterUserCommand::class => 'command.handler.register_user'
        ];
        $this->container->set('command.bus', function (ServiceContainer $container) {
            return new SynchronousCommandBus($container->get('command.router'));
        });
        $this->container->set('command.router', function (ServiceContainer $container) {
            return new CommandRouter($container->get('command.service_map'));
        });
        $this->container->set('command.service_map', function (ServiceContainer $container) {
            $serviceMap = new ServiceAwareCommandMap($container);
            $serviceMap->registerHandlers($container['command.handlers']);

            return $serviceMap;
        });
        $this->container->set('command.handler.register_user', function () {
            return new RegisterUserHandler();
        });
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
        /** @var CommandBusInterface $commandBus */
        $commandBus = $this->container->get('command.bus');
        $commandBus->execute($command);
        /** @var RegisterUserHandler $handler */
        $handler = $this->container->get('command.handler.register_user');
        $this->assertTrue($handler->isHandled());
    }

    public function test_that_map_has_handler_returns_true_when_handler_registered()
    {
        $commandMap = $this->container->get('command.service_map');
        $this->assertTrue($commandMap->hasHandler(RegisterUserCommand::class));
    }

    public function test_that_map_has_handler_returns_false_when_handler_not_registered()
    {
        $this->container['command.handlers'] = [];
        $commandMap = $this->container->get('command.service_map');
        $this->assertFalse($commandMap->hasHandler(RegisterUserCommand::class));
    }

    /**
     * @expectedException \Novuso\System\Exception\DomainException
     */
    public function test_that_command_map_throws_exception_when_command_class_is_invalid()
    {
        $commandMap = $this->container->get('command.service_map');
        $commandMap->registerHandler('FooBar', 'some.service');
    }

    /**
     * @expectedException \Novuso\System\Exception\LookupException
     */
    public function test_that_command_map_throws_exception_when_handler_is_not_registered()
    {
        $this->container['command.handlers'] = [];
        $commandMap = $this->container->get('command.service_map');
        $commandMap->getHandler(RegisterUserCommand::class);
    }
}
