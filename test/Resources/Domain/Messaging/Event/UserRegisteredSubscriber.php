<?php

namespace Novuso\Test\Common\Resources\Domain\Messaging\Event;

use Novuso\Common\Domain\Messaging\Event\EventMessage;
use Novuso\Common\Domain\Messaging\Event\EventSubscriber;
use Novuso\System\Utility\ClassName;

class UserRegisteredSubscriber implements EventSubscriber
{
    protected $users = [];

    public static function eventRegistration(): array
    {
        return [
            ClassName::underscore(UserRegisteredEvent::class) => 'onUserRegistered',
            'other.event'                                     => ['onOtherEvent', 10],
            'some.event'                                      => [
                ['onSomeEventSecond', 10],
                ['onSomeEventThird', 5],
                ['onSomeEventFirst', 100]
            ]
        ];
    }

    public function onUserRegistered(EventMessage $message)
    {
        /** @var UserRegisteredEvent $event */
        $event = $message->payload();
        $email = $event->email();
        $this->users[$email] = $event->toArray();
    }

    public function onOtherEvent(EventMessage $message)
    {
    }

    public function onSomeEventFirst(EventMessage $message)
    {
    }

    public function onSomeEventSecond(EventMessage $message)
    {
    }

    public function onSomeEventThird(EventMessage $message)
    {
    }

    public function isUserRegistered(string $email)
    {
        return isset($this->users[$email]);
    }
}
