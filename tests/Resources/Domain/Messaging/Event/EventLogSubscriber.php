<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Resources\Domain\Messaging\Event;

use Novuso\Common\Domain\Messaging\Event\AllEvents;
use Novuso\Common\Domain\Messaging\Event\EventMessage;
use Novuso\Common\Domain\Messaging\Event\EventSubscriber;

/**
 * Class EventLogSubscriber
 */
class EventLogSubscriber implements EventSubscriber
{
    protected array $logs = [];

    public static function eventRegistration(): array
    {
        return [AllEvents::class => 'onAllEvents'];
    }

    public function onAllEvents(EventMessage $message): void
    {
        $this->logs[] = $message->toString();
    }

    public function getLogs(): array
    {
        return $this->logs;
    }
}
