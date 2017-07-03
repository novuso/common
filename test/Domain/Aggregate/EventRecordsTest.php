<?php

namespace Novuso\Test\Common\Domain\Aggregate;

use Novuso\Test\Common\Resources\Domain\Messaging\Event\UserRegisteredEvent;
use Novuso\Test\Common\Resources\Domain\Model\User;
use Novuso\Test\System\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Domain\Aggregate\EventRecords
 */
class EventRecordsTest extends UnitTestCase
{
    public function test_that_event_is_recorded()
    {
        $user = User::register('jsmith@example.com', 'James', 'Smith', 'D');
        $events = $user->extractRecordedEvents();
        /** @var UserRegisteredEvent $event */
        $event = $events[0];
        $this->assertSame('jsmith@example.com', $event->email());
    }
}
