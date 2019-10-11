<?php declare(strict_types=1);

namespace Novuso\Common\Test\Application\Messaging\Command;

use Mockery\MockInterface;
use Novuso\Common\Application\Messaging\Command\QueueingCommandBus;
use Novuso\Common\Application\Messaging\MessageQueue;
use Novuso\Common\Domain\Messaging\Message;
use Novuso\Common\Test\Resources\Domain\Messaging\Command\RegisterUserCommand;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Messaging\Command\QueueingCommandBus
 */
class QueueingCommandBusTest extends UnitTestCase
{
    /** @var QueueingCommandBus */
    protected $commandBus;
    /** @var MessageQueue|MockInterface */
    protected $mockMessageQueue;
    /** @var string */
    protected $queueName;

    protected function setUp(): void
    {
        $this->mockMessageQueue = $this->mock(MessageQueue::class);
        $this->queueName = 'command';
        $this->commandBus = new QueueingCommandBus($this->mockMessageQueue, $this->queueName);
    }

    public function test_that_execute_delegates_to_message_queue()
    {
        $command = (new RegisterUserCommand())
            ->setEmail('ljenkins@example.com')
            ->setFirstName('leeroy')
            ->setLastName('jenkins');

        $this->mockMessageQueue
            ->shouldReceive('enqueue')
            ->once()
            ->withArgs(function ($name, Message $message) {
                return $this->queueName === $name
                    && $message->payloadType()->toClassName() === RegisterUserCommand::class;
            })
            ->andReturnNull();

        $this->commandBus->execute($command);
    }
}
