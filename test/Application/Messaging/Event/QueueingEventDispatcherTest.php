<?php

namespace Novuso\Test\Common\Application\Messaging\Event;

use Novuso\Common\Application\Messaging\Event\QueueingEventDispatcher;
use Novuso\Common\Application\Messaging\MessageQueue;
use Novuso\Common\Domain\Messaging\Event\EventMessage;
use Novuso\Test\Common\Resources\Domain\Messaging\Event\UserRegisteredEvent;
use Novuso\Test\Common\Resources\Domain\Messaging\Event\UserRegisteredSubscriber;
use Novuso\Test\System\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Messaging\Event\QueueingEventDispatcher
 */
class QueueingEventDispatcherTest extends UnitTestCase
{
    public function test_that_message_queue_receives_triggered_event()
    {
        $event = new UserRegisteredEvent('jsmith@example.com', 'James', 'Smith', 'D');
        $topic = 'event_queue';

        /** @var MessageQueue $messageQueue */
        $messageQueue = $this->mock(MessageQueue::class);
        $eventDispatcher = new QueueingEventDispatcher($messageQueue, $topic);

        $messageQueue
            ->shouldReceive('enqueue')
            ->once()
            ->andReturn(null);

        $eventDispatcher->trigger($event);
    }

    public function test_that_message_queue_receives_dispatched_message()
    {
        $event = new UserRegisteredEvent('jsmith@example.com', 'James', 'Smith', 'D');
        $message = EventMessage::create($event);
        $topic = 'event_queue';

        /** @var MessageQueue $messageQueue */
        $messageQueue = $this->mock(MessageQueue::class);
        $eventDispatcher = new QueueingEventDispatcher($messageQueue, $topic);

        $messageQueue
            ->shouldReceive('enqueue')
            ->with($topic, $message)
            ->once()
            ->andReturn(null);

        $eventDispatcher->dispatch($message);
    }

    public function test_that_register_is_no_op()
    {
        $topic = 'event_queue';
        /** @var MessageQueue $messageQueue */
        $messageQueue = $this->mock(MessageQueue::class);
        $eventDispatcher = new QueueingEventDispatcher($messageQueue, $topic);

        $eventDispatcher->register(new UserRegisteredSubscriber());

        $this->assertTrue(true);
    }

    public function test_that_unregister_is_no_op()
    {
        $topic = 'event_queue';
        /** @var MessageQueue $messageQueue */
        $messageQueue = $this->mock(MessageQueue::class);
        $eventDispatcher = new QueueingEventDispatcher($messageQueue, $topic);

        $eventDispatcher->unregister(new UserRegisteredSubscriber());

        $this->assertTrue(true);
    }

    public function test_that_add_handler_is_no_op()
    {
        $topic = 'event_queue';
        /** @var MessageQueue $messageQueue */
        $messageQueue = $this->mock(MessageQueue::class);
        $eventDispatcher = new QueueingEventDispatcher($messageQueue, $topic);

        $eventDispatcher->addHandler('foo', function () {
        });

        $this->assertTrue(true);
    }

    public function test_that_get_handlers_returns_empty_array()
    {
        $topic = 'event_queue';
        /** @var MessageQueue $messageQueue */
        $messageQueue = $this->mock(MessageQueue::class);
        $eventDispatcher = new QueueingEventDispatcher($messageQueue, $topic);

        $this->assertTrue(empty($eventDispatcher->getHandlers()));
    }

    public function test_that_has_handlers_returns_false()
    {
        $topic = 'event_queue';
        /** @var MessageQueue $messageQueue */
        $messageQueue = $this->mock(MessageQueue::class);
        $eventDispatcher = new QueueingEventDispatcher($messageQueue, $topic);

        $this->assertFalse($eventDispatcher->hasHandlers());
    }

    public function test_that_remove_handler_is_no_op()
    {
        $topic = 'event_queue';
        /** @var MessageQueue $messageQueue */
        $messageQueue = $this->mock(MessageQueue::class);
        $eventDispatcher = new QueueingEventDispatcher($messageQueue, $topic);

        $eventDispatcher->removeHandler('foo', function () {
        });

        $this->assertTrue(true);
    }
}
