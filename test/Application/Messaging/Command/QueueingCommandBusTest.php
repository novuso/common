<?php

namespace Novuso\Test\Common\Application\Messaging\Command;

use Novuso\Common\Application\Messaging\Command\QueueingCommandBus;
use Novuso\Common\Application\Messaging\MessageQueue;
use Novuso\Common\Domain\Messaging\Command\CommandMessage;
use Novuso\Test\Common\Resources\Domain\Messaging\Command\RegisterUserCommand;
use Novuso\Test\System\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Messaging\Command\QueueingCommandBus
 */
class QueueingCommandBusTest extends UnitTestCase
{
    public function test_that_message_queue_receives_executed_message()
    {
        $command = new RegisterUserCommand();
        $command
            ->setFirstName('James')
            ->setMiddleName('D')
            ->setLastName('Smith')
            ->setEmail('jsmith@example.com')
            ->setPassword('secret');
        $topic = 'command_queue';

        /** @var MessageQueue $messageQueue */
        $messageQueue = $this->mock(MessageQueue::class);
        $commandBus = new QueueingCommandBus($messageQueue, $topic);

        $messageQueue
            ->shouldReceive('enqueue')
            ->once()
            ->andReturn(null);

        $commandBus->execute($command);
    }

    public function test_that_message_queue_receives_dispatched_message()
    {
        $command = new RegisterUserCommand();
        $command
            ->setFirstName('James')
            ->setMiddleName('D')
            ->setLastName('Smith')
            ->setEmail('jsmith@example.com')
            ->setPassword('secret');
        $message = CommandMessage::create($command);
        $topic = 'command_queue';

        /** @var MessageQueue $messageQueue */
        $messageQueue = $this->mock(MessageQueue::class);
        $commandBus = new QueueingCommandBus($messageQueue, $topic);

        $messageQueue
            ->shouldReceive('enqueue')
            ->with($topic, $message)
            ->once()
            ->andReturn(null);

        $commandBus->dispatch($message);
    }
}
