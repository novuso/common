<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Application\Messaging\Command;

use Monolog\Handler\TestHandler;
use Monolog\Logger;
use Novuso\Common\Application\Messaging\Command\CommandPipeline;
use Novuso\Common\Application\Messaging\Command\Filter\CommandLogger;
use Novuso\Common\Application\Messaging\Command\Routing\InMemoryCommandMap;
use Novuso\Common\Application\Messaging\Command\Routing\SimpleCommandRouter;
use Novuso\Common\Application\Messaging\Command\RoutingCommandBus;
use Novuso\Common\Test\Resources\Domain\Messaging\Command\RegisterUserCommand;
use Novuso\Common\Test\Resources\Domain\Messaging\Command\RegisterUserHandler;
use Novuso\System\Exception\AssertionException;
use Novuso\System\Exception\LookupException;
use Novuso\System\Test\TestCase\UnitTestCase;
use Novuso\System\Utility\ClassName;

/**
 * @covers \Novuso\Common\Application\Messaging\Command\Filter\CommandLogger
 * @covers \Novuso\Common\Application\Messaging\Command\Routing\SimpleCommandRouter
 * @covers \Novuso\Common\Application\Messaging\Command\Routing\InMemoryCommandMap
 * @covers \Novuso\Common\Application\Messaging\Command\CommandPipeline
 * @covers \Novuso\Common\Application\Messaging\Command\RoutingCommandBus
 */
class CommandPipelineTest extends UnitTestCase
{
    /** @var CommandPipeline */
    protected $pipeline;
    /** @var RoutingCommandBus */
    protected $commandBus;
    /** @var SimpleCommandRouter */
    protected $commandRouter;
    /** @var InMemoryCommandMap */
    protected $commandMap;
    /** @var CommandLogger */
    protected $commandLogger;
    /** @var TestHandler */
    protected $logHandler;
    /** @var Logger */
    protected $logger;

    protected function setUp(): void
    {
        $this->logHandler = new TestHandler();
        $this->logger = new Logger('test');
        $this->logger->pushHandler($this->logHandler);
        $this->commandLogger = new CommandLogger($this->logger);
        $this->commandMap = new InMemoryCommandMap();
        $this->commandRouter = new SimpleCommandRouter($this->commandMap);
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

        static::assertTrue(
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

    public function test_that_command_is_executed_by_command_bus()
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
        $this->commandBus->execute($command);

        static::assertTrue($handler->isHandled());
    }

    public function test_that_command_map_throws_exception_when_command_class_is_invalid()
    {
        $this->expectException(AssertionException::class);

        $handler = new RegisterUserHandler();
        $this->commandMap->registerHandler('FooBar', $handler);
    }

    public function test_that_command_map_throws_exception_when_handler_is_not_registered()
    {
        $this->expectException(LookupException::class);

        $this->commandMap->getHandler(RegisterUserCommand::class);
    }
}
