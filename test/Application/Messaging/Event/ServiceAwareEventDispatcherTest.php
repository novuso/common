<?php

namespace Novuso\Test\Common\Application\Messaging\Event;

use Novuso\Common\Adapter\Service\ServiceContainer;
use Novuso\Common\Application\Messaging\Event\ServiceAwareEventDispatcher;
use Novuso\System\Utility\ClassName;
use Novuso\Test\Common\Resources\Domain\Messaging\Event\UserRegisteredEvent;
use Novuso\Test\Common\Resources\Domain\Messaging\Event\UserRegisteredSubscriber;
use Novuso\Test\System\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Messaging\Event\ServiceAwareEventDispatcher
 */
class ServiceAwareEventDispatcherTest extends UnitTestCase
{
    /**
     * @var ServiceContainer
     */
    protected $container;

    protected function setUp()
    {
        $this->container = require __DIR__.'/Resources/services.php';
    }

    public function test_that_event_is_dispatched_to_registered_subscriber()
    {
        /** @var ServiceAwareEventDispatcher $dispatcher */
        $dispatcher = $this->container->get('event.dispatcher');
        $event = new UserRegisteredEvent('jsmith@example.com', 'James', 'Smith', 'D');
        $dispatcher->trigger($event);
        /** @var UserRegisteredSubscriber $subscriber */
        $subscriber = $this->container->get('subscriber.user_registered');
        $this->assertTrue($subscriber->isUserRegistered('jsmith@example.com'));
    }

    public function test_that_get_handlers_returns_expected_value_for_all_events()
    {
        /** @var ServiceAwareEventDispatcher $dispatcher */
        $dispatcher = $this->container->get('event.dispatcher');
        // handlers registered for three different events
        $this->assertSame(3, count($dispatcher->getHandlers()));
    }

    public function test_that_get_handlers_returns_expected_value_for_event_type()
    {
        /** @var ServiceAwareEventDispatcher $dispatcher */
        $dispatcher = $this->container->get('event.dispatcher');
        $type = ClassName::underscore(UserRegisteredEvent::class);
        // one handler registered for UserRegisteredEvent
        $this->assertSame(1, count($dispatcher->getHandlers($type)));
    }

    public function test_that_has_handlers_returns_true_when_there_are_handlers_all_events()
    {
        /** @var ServiceAwareEventDispatcher $dispatcher */
        $dispatcher = $this->container->get('event.dispatcher');
        $this->assertTrue($dispatcher->hasHandlers());
    }

    public function test_that_has_handlers_returns_true_when_there_are_handlers_event_type()
    {
        /** @var ServiceAwareEventDispatcher $dispatcher */
        $dispatcher = $this->container->get('event.dispatcher');
        $type = ClassName::underscore(UserRegisteredEvent::class);
        $this->assertTrue($dispatcher->hasHandlers($type));
    }

    public function test_that_has_handlers_returns_true_with_handlers_added_in_memory()
    {
        $dispatcher = new ServiceAwareEventDispatcher($this->container);
        $type = ClassName::underscore(UserRegisteredEvent::class);
        $dispatcher->addHandler($type, function () {});
        $this->assertTrue($dispatcher->hasHandlers($type));
    }

    public function test_that_remove_handler_removes_handlers_as_expected()
    {
        /** @var ServiceAwareEventDispatcher $dispatcher */
        $dispatcher = $this->container->get('event.dispatcher');
        $subscriber = $this->container->get('subscriber.user_registered');
        $dispatcher->unregister($subscriber);
        $this->assertFalse($dispatcher->hasHandlers());
    }

    public function test_that_event_is_dispatched_correctly_when_service_is_changed()
    {
        /** @var ServiceAwareEventDispatcher $dispatcher */
        $dispatcher = $this->container->get('event.dispatcher');
        $event = new UserRegisteredEvent('jsmith@example.com', 'James', 'Smith', 'D');
        $dispatcher->trigger($event);
        $this->container->set('subscriber.user_registered', function () {
            return new UserRegisteredSubscriber();
        });
        $subscriber = $this->container->get('subscriber.user_registered');
        $dispatcher->trigger($event);
        $this->assertTrue($subscriber->isUserRegistered('jsmith@example.com'));
    }
}
