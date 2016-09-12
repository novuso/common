<?php

namespace Novuso\Test\Common\Application\Messaging\Event;

use Novuso\Common\Application\Messaging\Event\SimpleEventDispatcher;
use Novuso\Test\Common\Resources\Domain\Messaging\Event\EventLogSubscriber;
use Novuso\Test\Common\Resources\Domain\Messaging\Event\UserRegisteredEvent;
use Novuso\Test\Common\Resources\Domain\Messaging\Event\UserRegisteredSubscriber;
use Novuso\Test\System\TestCase\UnitTestCase;

/**
 * @covers Novuso\Common\Application\Messaging\Event\SimpleEventDispatcher
 */
class SimpleEventDispatcherTest extends UnitTestCase
{
    /**
     * @var SimpleEventDispatcher
     */
    protected $dispatcher;

    protected function setUp()
    {
        $this->dispatcher = new SimpleEventDispatcher();
    }

    public function test_that_event_is_dispatched_to_registered_subscriber()
    {
        $subscriber = new UserRegisteredSubscriber();
        $this->dispatcher->register($subscriber);
        $event = new UserRegisteredEvent('jsmith@example.com', 'James', 'Smith', 'D');
        $this->dispatcher->dispatch($event);
        $this->assertTrue($subscriber->isUserRegistered('jsmith@example.com'));
    }

    public function test_that_aggregate_event_is_dispatched_as_expected()
    {
        $subscriber = new UserRegisteredSubscriber();
        $this->dispatcher->register($subscriber);
        $events = [new UserRegisteredEvent('jsmith@example.com', 'James', 'Smith', 'D')];
        $aggregate = $this->mock('Novuso\\Common\\Domain\\Model\\Api\\AggregateRoot');
        $aggregate
            ->shouldReceive('extractRecordedEvents')
            ->once()
            ->andReturn($events);
        $this->dispatcher->dispatchEvents($aggregate);
        $this->assertTrue($subscriber->isUserRegistered('jsmith@example.com'));
    }

    public function test_that_event_is_not_dispatched_to_unregistered_subscriber()
    {
        $subscriber = new UserRegisteredSubscriber();
        $this->dispatcher->register($subscriber);
        $this->dispatcher->unregister($subscriber);
        $event = new UserRegisteredEvent('jsmith@example.com', 'James', 'Smith', 'D');
        $this->dispatcher->dispatch($event);
        $this->assertFalse($subscriber->isUserRegistered('jsmith@example.com'));
    }

    public function test_that_has_handlers_returns_true_when_there_are_handlers()
    {
        $subscriber = new UserRegisteredSubscriber();
        $this->dispatcher->register($subscriber);
        $this->assertTrue($this->dispatcher->hasHandlers());
    }

    public function test_that_remove_handler_does_not_error_when_handler_not_registered()
    {
        $this->dispatcher->removeHandler('foo', function () {});
        $this->assertFalse($this->dispatcher->hasHandlers());
    }

    public function test_that_all_events_key_subscribes_to_any_event()
    {
        $subscriber = new EventLogSubscriber();
        $this->dispatcher->register($subscriber);
        $event = new UserRegisteredEvent('jsmith@example.com', 'James', 'Smith', 'D');
        $this->dispatcher->dispatch($event);
        $logs = $subscriber->getLogs();
        $payload = '{prefix:NULL,first_name:James,middle_name:D,last_name:Smith,suffix:NULL,email:jsmith@example.com}';
        $this->assertContains($payload, $logs[0]);
    }
}
