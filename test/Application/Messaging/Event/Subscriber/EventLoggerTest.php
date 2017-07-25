<?php

namespace Novuso\Test\Common\Application\Messaging\Event\Subscriber;

use Monolog\Handler\TestHandler;
use Monolog\Logger;
use Novuso\Common\Application\Messaging\Event\Subscriber\EventLogger;
use Novuso\Common\Application\Messaging\Event\SynchronousEventDispatcher;
use Novuso\System\Utility\ClassName;
use Novuso\Test\Common\Resources\Domain\Messaging\Event\UserRegisteredEvent;
use Novuso\Test\System\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Messaging\Event\Subscriber\EventLogger
 */
class EventLoggerTest extends UnitTestCase
{
    /** @var SynchronousEventDispatcher */
    protected $dispatcher;
    /** @var EventLogger */
    protected $subscriber;
    /** @var TestHandler */
    protected $logHandler;
    /** @var Logger */
    protected $logger;

    protected function setUp()
    {
        parent::setUp();
        $this->logHandler = new TestHandler();
        $this->logger = new Logger('test');
        $this->logger->pushHandler($this->logHandler);
        $this->dispatcher = new SynchronousEventDispatcher();
        $this->subscriber = new EventLogger($this->logger);
        $this->dispatcher->register($this->subscriber);
    }

    public function test_that_event_is_logged_by_subscriber()
    {
        $event = new UserRegisteredEvent('jsmith@example.com', 'James', 'Smith', 'D');
        $this->dispatcher->trigger($event);
        $this->assertTrue($this->logHandler->hasInfoThatContains(sprintf(
            'Event dispatched {%s}',
            ClassName::canonical(UserRegisteredEvent::class)
        )));
    }
}
