<?php declare(strict_types=1);

namespace Novuso\Common\Test\Application\Messaging;

use Mockery\MockInterface;
use Novuso\Common\Application\Messaging\Exception\MessageQueueException;
use Novuso\Common\Application\Messaging\MessageQueue;
use Novuso\Common\Application\Messaging\MessageStore;
use Novuso\Common\Application\Messaging\RecyclingMessageQueue;
use Novuso\Common\Domain\Messaging\Message;
use Novuso\Common\Domain\Messaging\MessageId;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Messaging\RecyclingMessageQueue
 */
class RecyclingMessageQueueTest extends UnitTestCase
{
    /** @var RecyclingMessageQueue */
    protected $messageQueue;
    /** @var MessageQueue|MockInterface */
    protected $mockMessageQueue;
    /** @var MessageStore|MockInterface */
    protected $mockMessageStore;

    protected function setUp(): void
    {
        $this->mockMessageQueue = $this->mock(MessageQueue::class);
        $this->mockMessageStore = $this->mock(MessageStore::class);
        $this->messageQueue = new RecyclingMessageQueue(
            $this->mockMessageQueue,
            $this->mockMessageStore
        );
    }

    public function test_that_recycle_delegates_to_services()
    {
        $messageId = MessageId::generate();
        $queue = 'commands';

        /** @var Message|MockInterface $mockMessage */
        $mockMessage = $this->mock(Message::class);

        $mockMessage
            ->shouldReceive('withMetaData')
            ->once()
            ->andReturn($mockMessage);

        $this->mockMessageStore
            ->shouldReceive('get')
            ->once()
            ->with($queue, $messageId)
            ->andReturn($mockMessage);

        $this->mockMessageQueue
            ->shouldReceive('enqueue')
            ->once()
            ->with($queue, $mockMessage)
            ->andReturnNull();

        $this->mockMessageStore
            ->shouldReceive('remove')
            ->once()
            ->with($queue, $messageId)
            ->andReturnNull();

        $this->messageQueue->recycle($queue, $messageId);
    }

    public function test_that_recycle_all_delegates_to_services()
    {
        $queue = 'commands';
        $messageId = MessageId::generate();

        /** @var Message|MockInterface $mockMessage */
        $mockMessage = $this->mock(Message::class);

        $mockMessage
            ->shouldReceive('id')
            ->once()
            ->andReturn($messageId);

        $messages = [$mockMessage];

        $this->mockMessageStore
            ->shouldReceive('getAll')
            ->once()
            ->with($queue)
            ->andReturn($messages);

        $this->mockMessageQueue
            ->shouldReceive('enqueue')
            ->once()
            ->with($queue, $mockMessage)
            ->andReturnNull();

        $this->mockMessageStore
            ->shouldReceive('remove')
            ->once()
            ->with($queue, $messageId)
            ->andReturnNull();

        $this->messageQueue->recycleAll($queue);
    }

    public function test_that_enqueue_delegates_to_services()
    {
        $queue = 'commands';

        /** @var Message|MockInterface $mockMessage */
        $mockMessage = $this->mock(Message::class);

        $this->mockMessageQueue
            ->shouldReceive('enqueue')
            ->once()
            ->with($queue, $mockMessage)
            ->andReturnNull();

        $this->messageQueue->enqueue($queue, $mockMessage);
    }

    public function test_that_dequeue_delegates_to_services()
    {
        $queue = 'commands';
        $timeout = 0;

        /** @var Message|MockInterface $mockMessage */
        $mockMessage = $this->mock(Message::class);

        $this->mockMessageQueue
            ->shouldReceive('dequeue')
            ->once()
            ->with($queue, $timeout)
            ->andReturn($mockMessage);

        $this->messageQueue->dequeue($queue, $timeout);
    }

    public function test_that_dequeue_non_blocking_delegates_to_services()
    {
        $queue = 'commands';

        /** @var Message|MockInterface $mockMessage */
        $mockMessage = $this->mock(Message::class);

        $this->mockMessageQueue
            ->shouldReceive('dequeueNonBlocking')
            ->once()
            ->with($queue)
            ->andReturn($mockMessage);

        $this->messageQueue->dequeueNonBlocking($queue);
    }

    public function test_that_acknowledge_delegates_to_services()
    {
        $queue = 'commands';

        /** @var Message|MockInterface $mockMessage */
        $mockMessage = $this->mock(Message::class);

        $this->mockMessageQueue
            ->shouldReceive('acknowledge')
            ->once()
            ->with($queue, $mockMessage)
            ->andReturnNull();

        $this->messageQueue->acknowledge($queue, $mockMessage);
    }

    public function test_that_reject_delegates_to_services_false_requeue()
    {
        $queue = 'commands';
        $requeue = false;

        /** @var Message|MockInterface $mockMessage */
        $mockMessage = $this->mock(Message::class);

        $this->mockMessageQueue
            ->shouldReceive('reject')
            ->once()
            ->with($queue, $mockMessage, $requeue)
            ->andReturnNull();

        $this->mockMessageStore
            ->shouldReceive('add')
            ->once()
            ->with($queue, $mockMessage)
            ->andReturnNull();

        $this->messageQueue->reject($queue, $mockMessage, $requeue);
    }

    public function test_that_reject_delegates_to_services_true_requeue()
    {
        $queue = 'commands';
        $requeue = true;

        /** @var Message|MockInterface $mockMessage */
        $mockMessage = $this->mock(Message::class);

        $this->mockMessageQueue
            ->shouldReceive('reject')
            ->once()
            ->with($queue, $mockMessage, $requeue)
            ->andReturnNull();

        $this->messageQueue->reject($queue, $mockMessage, $requeue);
    }

    public function test_that_recycle_throws_exception_on_error()
    {
        $this->expectException(MessageQueueException::class);

        $messageId = MessageId::generate();
        $queue = 'commands';

        $this->mockMessageStore
            ->shouldReceive('get')
            ->once()
            ->with($queue, $messageId)
            ->andReturn(null);

        $this->messageQueue->recycle($queue, $messageId);
    }

    public function test_that_recycle_all_throws_exception_on_error()
    {
        $this->expectException(MessageQueueException::class);

        $queue = 'commands';

        $this->mockMessageStore
            ->shouldReceive('getAll')
            ->once()
            ->with($queue)
            ->andThrow(new \Exception('Something went wrong'));

        $this->messageQueue->recycleAll($queue);
    }

    public function test_that_reject_throws_exception_on_error()
    {
        $this->expectException(MessageQueueException::class);

        $queue = 'commands';
        $requeue = false;

        /** @var Message|MockInterface $mockMessage */
        $mockMessage = $this->mock(Message::class);

        $this->mockMessageQueue
            ->shouldReceive('reject')
            ->once()
            ->with($queue, $mockMessage, $requeue)
            ->andReturnNull();

        $this->mockMessageStore
            ->shouldReceive('add')
            ->once()
            ->with($queue, $mockMessage)
            ->andThrow(new \Exception('Something went wrong'));

        $this->messageQueue->reject($queue, $mockMessage, $requeue);
    }
}
