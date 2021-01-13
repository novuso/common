<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Resources\Domain\Messaging\Event;

use Novuso\Common\Domain\Messaging\Event\EventMessage;
use Novuso\Common\Domain\Messaging\Event\EventSubscriber;

/**
 * Class UserRegisteredSubscriber
 */
class UserRegisteredSubscriber implements EventSubscriber
{
    protected array $users = [];

    public static function eventRegistration(): array
    {
        return [
            UserRegisteredEvent::class => 'onUserRegistered',
            'other.event'              => ['onOtherEvent', 10],
            'some.event'               => [
                ['onSomeEventSecond', 10],
                ['onSomeEventThird', 5],
                ['onSomeEventFirst', 100]
            ]
        ];
    }

    public function onUserRegistered(EventMessage $message): void
    {
        /** @var UserRegisteredEvent $event */
        $event = $message->payload();
        $email = $event->email();
        $this->users[$email] = $event->toArray();
    }

    public function onOtherEvent(EventMessage $message): void
    {
    }

    public function onSomeEventFirst(EventMessage $message): void
    {
    }

    public function onSomeEventSecond(EventMessage $message): void
    {
    }

    public function onSomeEventThird(EventMessage $message): void
    {
    }

    public function isUserRegistered(string $email): bool
    {
        return isset($this->users[$email]);
    }
}
