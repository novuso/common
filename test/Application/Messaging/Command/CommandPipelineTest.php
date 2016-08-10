<?php

namespace Novuso\Test\Common\Application\Messaging\Command;

use Monolog\Handler\TestHandler;
use Monolog\Logger;
use Novuso\Common\Application\Messaging\Command\CommandPipeline;
use Novuso\Common\Application\Messaging\Command\Filter\CommandLogger;
use Novuso\Common\Application\Messaging\Command\Routing\InMemoryCommandMap;
use Novuso\Common\Application\Messaging\Command\Routing\InMemoryCommandRouter;
use Novuso\Common\Application\Messaging\Command\RoutingCommandBus;
use Novuso\System\Utility\ClassName;
use Novuso\Test\Common\Resources\Domain\Messaging\Command\RegisterUserCommand;
use Novuso\Test\Common\Resources\Domain\Messaging\Command\RegisterUserHandler;
use Novuso\Test\System\TestCase\UnitTestCase;

/**
 * @covers Novuso\Common\Application\Messaging\Command\Filter\CommandLogger
 * @covers Novuso\Common\Application\Messaging\Command\Routing\InMemoryCommandMap
 * @covers Novuso\Common\Application\Messaging\Command\Routing\InMemoryCommandRouter
 * @covers Novuso\Common\Application\Messaging\Command\CommandPipeline
 * @covers Novuso\Common\Application\Messaging\Command\RoutingCommandBus
 */
class CommandPipelineTest extends UnitTestCase
{
    /** @var CommandPipeline */
    protected $pipeline;
    /** @var RoutingCommandBus */
    protected $commandBus;
    /** @var InMemoryCommandRouter */
    protected $commandRouter;
    /** @var InMemoryCommandMap */
    protected $commandMap;
    /** @var CommandLogger */
    protected $commandLogger;
    /** @var TestHandler */
    protected $logHandler;
    /** @var Logger */
    protected $logger;

    protected function setUp()
    {
        $this->logHandler = new TestHandler();
        $this->logger = new Logger('test');
        $this->logger->pushHandler($this->logHandler);
        $this->commandLogger = new CommandLogger($this->logger);
        $this->commandMap = new InMemoryCommandMap();
        $this->commandRouter = new InMemoryCommandRouter($this->commandMap);
        $this->commandBus = new RoutingCommandBus($this->commandRouter);
        $this->pipeline = new CommandPipeline($this->commandBus);
        $this->pipeline->addFilter($this->commandLogger);
    }

    public function test_that_command_is_executed_by_pipeline()
    {
        $handler = new RegisterUserHandler();
        $this->commandMap->registerHandlers([RegisterUserCommand::class => $handler]);
        $command = new RegisterUserCommand();
        $command
            ->setFirstName('James')
            ->setMiddleName('D')
            ->setLastName('Smith')
            ->setEmail('jsmith@example.com')
            ->setPassword('secret');
        $this->pipeline->execute($command);
        $this->assertTrue(
            $this->commandMap->hasHandler(RegisterUserCommand::class)
            && $this->logHandler->hasInfoThatContains(sprintf(
                'Command received {%s}',
                ClassName::canonical(RegisterUserCommand::class)
            ))
            && $this->logHandler->hasInfoThatContains(sprintf(
                'Command handled {%s}',
                ClassName::canonical(RegisterUserCommand::class)
            ))
            && $handler->isHandled()
        );
    }

    /**
     * @expectedException \Novuso\System\Exception\DomainException
     */
    public function test_that_command_map_throws_exception_when_command_class_is_invalid()
    {
        $handler = new RegisterUserHandler();
        $this->commandMap->registerHandler('FooBar', $handler);
    }

    /**
     * @expectedException \Novuso\System\Exception\LookupException
     */
    public function test_that_command_map_throws_exception_when_handler_is_not_registered()
    {
        $this->commandMap->getHandler(RegisterUserCommand::class);
    }
}
