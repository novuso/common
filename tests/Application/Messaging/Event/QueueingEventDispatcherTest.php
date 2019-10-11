<?php declare(strict_types=1);

namespace Novuso\Common\Test\Application\Messaging\Event;

use Mockery\MockInterface;
use Novuso\Common\Application\Messaging\Event\QueueingEventDispatcher;
use Novuso\Common\Application\Messaging\MessageQueue;
use Novuso\Common\Domain\Messaging\Event\AllEvents;
use Novuso\Common\Domain\Messaging\Event\EventSubscriber;
use Novuso\Common\Domain\Messaging\Message;
use Novuso\Common\Test\Resources\Domain\Messaging\Event\UserRegisteredEvent;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Messaging\Event\QueueingEventDispatcher
 */
class QueueingEventDispatcherTest extends UnitTestCase
{
    /** @var QueueingEventDispatcher */
    protected $eventDispatcher;
    /** @var MessageQueue|MockInterface */
    protected $mockMessageQueue;
    /** @var string */
    protected $queueName;

    protected function setUp(): void
    {
        $this->mockMessageQueue = $this->mock(MessageQueue::class);
        $this->queueName = 'event';
        $this->eventDispatcher = new QueueingEventDispatcher($this->mockMessageQueue, $this->queueName);
    }

    public function test_that_trigger_delegates_to_message_queue()
    {
        $event = new UserRegisteredEvent('ljenkins@example.com', 'leeroy', 'jenkins');

        $this->mockMessageQueue
            ->shouldReceive('enqueue')
            ->once()
            ->withArgs(function ($name, Message $message) {
                return $this->queueName === $name
                    && $message->payloadType()->toClassName() === UserRegisteredEvent::class;
            })
            ->andReturnNull();

        $this->eventDispatcher->trigger($event);
    }

    public function test_that_register_does_nothing()
    {
        /** @var EventSubscriber|MockInterface $eventSubscriber */
        $eventSubscriber = $this->mock(EventSubscriber::class);
        $eventSubscriber
            ->shouldNotReceive('eventRegistration');
        $this->eventDispatcher->register($eventSubscriber);
    }

    public function test_that_unregister_does_nothing()
    {
        /** @var EventSubscriber|MockInterface $eventSubscriber */
        $eventSubscriber = $this->mock(EventSubscriber::class);
        $eventSubscriber
            ->shouldNotReceive('eventRegistration');
        $this->eventDispatcher->unregister($eventSubscriber);
    }

    public function test_that_add_handler_does_nothing()
    {
        $event = new UserRegisteredEvent('ljenkins@example.com', 'leeroy', 'jenkins');

        $this->eventDispatcher->addHandler(AllEvents::class, function () {
            throw new \Exception('This should not be called');
        });

        $this->mockMessageQueue
            ->shouldReceive('enqueue')
            ->once()
            ->withArgs(function ($name, Message $message) {
                return $this->queueName === $name
                    && $message->payloadType()->toClassName() === UserRegisteredEvent::class;
            })
            ->andReturnNull();

        $this->eventDispatcher->trigger($event);
    }

    public function test_that_get_handlers_returns_an_empty_array()
    {
        $this->eventDispatcher->addHandler(AllEvents::class, function () {
            throw new \Exception('This should not be called');
        });

        $this->assertEmpty($this->eventDispatcher->getHandlers());
    }

    public function test_that_has_handlers_returns_false()
    {
        $this->eventDispatcher->addHandler(AllEvents::class, function () {
            throw new \Exception('This should not be called');
        });

        $this->assertFalse($this->eventDispatcher->hasHandlers());
    }

    public function test_that_remove_handler_does_nothing()
    {
        $this->eventDispatcher->removeHandler(AllEvents::class, function () {
            throw new \Exception('This should not be called');
        });

        $this->assertFalse($this->eventDispatcher->hasHandlers());
    }
}
