<?php

use Novuso\Common\Application\Messaging\Event\ServiceAwareEventDispatcher;
use Novuso\Common\Application\Service\ServiceContainer;
use Novuso\Test\Common\Resources\Domain\Messaging\Event\UserRegisteredSubscriber;

$container = new ServiceContainer();

$container->set('event.dispatcher', function ($container) {
    $dispatcher = new ServiceAwareEventDispatcher($container);
    $dispatcher->registerService(UserRegisteredSubscriber::class, 'subscriber.user_registered');

    return $dispatcher;
});

$container->set('subscriber.user_registered', function () {
    return new UserRegisteredSubscriber();
});

return $container;
