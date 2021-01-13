<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Application\Messaging\Event;

use Novuso\Common\Application\Messaging\Event\SimpleEventDispatcher;
use Novuso\Common\Domain\Type\StringObject;
use Novuso\Common\Test\Resources\Domain\Messaging\Event\EventLogSubscriber;
use Novuso\Common\Test\Resources\Domain\Messaging\Event\UserRegisteredEvent;
use Novuso\Common\Test\Resources\Domain\Messaging\Event\UserRegisteredSubscriber;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Messaging\Event\SimpleEventDispatcher
 */
class SimpleEventDispatcherTest extends UnitTestCase
{
    /** @var SimpleEventDispatcher */
    protected $dispatcher;

    protected function setUp(): void
    {
        $this->dispatcher = new SimpleEventDispatcher();
    }

    public function test_that_event_is_dispatched_to_registered_subscriber()
    {
        $subscriber = new UserRegisteredSubscriber();
        $this->dispatcher->register($subscriber);
        $event = new UserRegisteredEvent('jsmith@example.com', 'James', 'Smith', 'D');
        $this->dispatcher->trigger($event);

        static::assertTrue($subscriber->isUserRegistered('jsmith@example.com'));
    }

    public function test_that_event_is_not_dispatched_to_unregistered_subscriber()
    {
        $subscriber = new UserRegisteredSubscriber();
        $this->dispatcher->register($subscriber);
        $this->dispatcher->unregister($subscriber);
        $event = new UserRegisteredEvent('jsmith@example.com', 'James', 'Smith', 'D');
        $this->dispatcher->trigger($event);

        static::assertFalse($subscriber->isUserRegistered('jsmith@example.com'));
    }

    public function test_that_has_handlers_returns_true_when_there_are_handlers()
    {
        $subscriber = new UserRegisteredSubscriber();
        $this->dispatcher->register($subscriber);

        static::assertTrue($this->dispatcher->hasHandlers());
    }

    public function test_that_remove_handler_does_not_error_when_handler_not_registered()
    {
        $this->dispatcher->removeHandler('foo', function () {});

        static::assertFalse($this->dispatcher->hasHandlers());
    }

    public function test_that_all_events_key_subscribes_to_any_event()
    {
        $subscriber = new EventLogSubscriber();
        $this->dispatcher->register($subscriber);
        $event = new UserRegisteredEvent('jsmith@example.com', 'James', 'Smith', 'D');
        $this->dispatcher->trigger($event);
        $logs = $subscriber->getLogs();
        $payload = '{"prefix":null,"first_name":"James","middle_name":"D","last_name":"Smith","suffix":null,'
            .'"email":"jsmith@example.com"}';

        static::assertTrue(StringObject::create($logs[0])->contains($payload));
    }
}
