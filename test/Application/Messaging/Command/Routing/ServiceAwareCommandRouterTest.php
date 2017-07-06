<?php

namespace Novuso\Test\Common\Application\Messaging\Command\Routing;

use Novuso\Common\Adapter\Service\ServiceContainer;
use Novuso\Common\Domain\Messaging\Command\CommandBus;
use Novuso\Test\Common\Resources\Domain\Messaging\Command\RegisterUserCommand;
use Novuso\Test\Common\Resources\Domain\Messaging\Command\RegisterUserHandler;
use Novuso\Test\System\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Messaging\Command\Routing\ServiceAwareCommandMap
 * @covers \Novuso\Common\Application\Messaging\Command\Routing\ServiceAwareCommandRouter
 */
class ServiceAwareCommandRouterTest extends UnitTestCase
{
    /**
     * @var ServiceContainer
     */
    protected $container;

    protected function setUp()
    {
        $this->container = require __DIR__.'/Resources/services.php';
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
