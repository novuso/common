<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Application\Messaging\Event\Subscriber;

use Monolog\Handler\TestHandler;
use Monolog\Logger;
use Novuso\Common\Application\Messaging\Event\SimpleEventDispatcher;
use Novuso\Common\Application\Messaging\Event\Subscriber\EventLogger;
use Novuso\Common\Test\Resources\Domain\Messaging\Event\UserRegisteredEvent;
use Novuso\System\Test\TestCase\UnitTestCase;
use Novuso\System\Utility\ClassName;

/**
 * @covers \Novuso\Common\Application\Messaging\Event\Subscriber\EventLogger
 */
class EventLoggerTest extends UnitTestCase
{
    /** @var SimpleEventDispatcher */
    protected $dispatcher;
    /** @var EventLogger */
    protected $subscriber;
    /** @var TestHandler */
    protected $logHandler;
    /** @var Logger */
    protected $logger;

    protected function setUp(): void
    {
        $this->logHandler = new TestHandler();
        $this->logger = new Logger('test');
        $this->logger->pushHandler($this->logHandler);
        $this->dispatcher = new SimpleEventDispatcher();
        $this->subscriber = new EventLogger($this->logger);
        $this->dispatcher->register($this->subscriber);
    }

    public function test_that_event_is_logged_by_subscriber()
    {
        $event = new UserRegisteredEvent('jsmith@example.com', 'James', 'Smith', 'D');
        $this->dispatcher->trigger($event);

        static::assertTrue($this->logHandler->hasInfoThatContains(sprintf(
            'Event dispatched {%s}',
            ClassName::canonical(UserRegisteredEvent::class)
        )));
    }
}
