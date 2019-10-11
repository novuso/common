<?php declare(strict_types=1);

namespace Novuso\Common\Test\Application\Messaging\Event;

use Novuso\Common\Application\Messaging\Event\ServiceAwareEventDispatcher;
use Novuso\Common\Application\Service\ServiceContainer;
use Novuso\Common\Domain\Messaging\Event\EventDispatcher;
use Novuso\Common\Test\Resources\Domain\Messaging\Event\UserRegisteredEvent;
use Novuso\Common\Test\Resources\Domain\Messaging\Event\UserRegisteredSubscriber;
use Novuso\System\Test\TestCase\UnitTestCase;
use Novuso\System\Utility\ClassName;

/**
 * @covers \Novuso\Common\Application\Messaging\Event\ServiceAwareEventDispatcher
 */
class ServiceAwareEventDispatcherTest extends UnitTestCase
{
    /** @var ServiceContainer */
    protected $container;

    protected function setUp(): void
    {
        $this->container = new ServiceContainer();
        $this->defineServices();
    }

    public function test_that_event_is_dispatched_to_registered_subscriber()
    {
        $event = new UserRegisteredEvent('jsmith@example.com', 'James', 'Smith', 'D');
        /** @var UserRegisteredSubscriber $subscriber */
        $subscriber = $this->container->get(UserRegisteredSubscriber::class);
        /** @var EventDispatcher $dispatcher */
        $dispatcher = $this->container->get(EventDispatcher::class);

        $dispatcher->trigger($event);

        $this->assertTrue($subscriber->isUserRegistered('jsmith@example.com'));
    }

    public function test_that_get_handlers_returns_expected_value_for_all_events()
    {
        /** @var EventDispatcher $dispatcher */
        $dispatcher = $this->container->get(EventDispatcher::class);

        $this->assertCount(3, $dispatcher->getHandlers());
    }

    public function test_that_get_handlers_returns_expected_value_for_event_type()
    {
        /** @var EventDispatcher $dispatcher */
        $dispatcher = $this->container->get(EventDispatcher::class);
        $type = ClassName::underscore(UserRegisteredEvent::class);

        $this->assertCount(1, $dispatcher->getHandlers($type));
    }

    public function test_that_has_handlers_returns_true_when_there_are_handlers_all_events()
    {
        /** @var EventDispatcher $dispatcher */
        $dispatcher = $this->container->get(EventDispatcher::class);

        $this->assertTrue($dispatcher->hasHandlers());
    }

    public function test_that_has_handlers_returns_true_when_there_are_handlers_event_type()
    {
        /** @var EventDispatcher $dispatcher */
        $dispatcher = $this->container->get(EventDispatcher::class);
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
        /** @var EventDispatcher $dispatcher */
        $dispatcher = $this->container->get(EventDispatcher::class);
        $subscriber = $this->container->get(UserRegisteredSubscriber::class);
        $dispatcher->unregister($subscriber);

        $this->assertFalse($dispatcher->hasHandlers());
    }

    public function test_that_event_is_dispatched_correctly_when_service_is_changed()
    {
        /** @var EventDispatcher $dispatcher */
        $dispatcher = $this->container->get(EventDispatcher::class);
        $event = new UserRegisteredEvent('jsmith@example.com', 'James', 'Smith', 'D');
        $dispatcher->trigger($event);
        $this->container->set(
            UserRegisteredSubscriber::class,
            function () {
                return new UserRegisteredSubscriber();
            }
        );
        /** @var UserRegisteredSubscriber $subscriber */
        $subscriber = $this->container->get(UserRegisteredSubscriber::class);
        $dispatcher->trigger($event);

        $this->assertTrue($subscriber->isUserRegistered('jsmith@example.com'));
    }

    protected function defineServices()
    {
        $this->container->set(
            EventDispatcher::class,
            function (ServiceContainer $container) {
                $dispatcher = new ServiceAwareEventDispatcher($container);

                $dispatcher->registerService(
                    UserRegisteredSubscriber::class,
                    UserRegisteredSubscriber::class
                );

                return $dispatcher;
            }
        );

        $this->container->set(
            UserRegisteredSubscriber::class,
            function () {
                return new UserRegisteredSubscriber();
            }
        );
    }
}
