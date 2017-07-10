<?php

use Novuso\Common\Application\Service\ServiceContainer;
use Novuso\Common\Application\Messaging\Command\Routing\ServiceAwareCommandMap;
use Novuso\Common\Application\Messaging\Command\Routing\ServiceAwareCommandRouter;
use Novuso\Common\Application\Messaging\Command\RoutingCommandBus;
use Novuso\Test\Common\Resources\Domain\Messaging\Command\RegisterUserCommand;
use Novuso\Test\Common\Resources\Domain\Messaging\Command\RegisterUserHandler;

$container = new ServiceContainer();

$container['command.handlers'] = [
    RegisterUserCommand::class => 'command.handler.register_user'
];

$container->set('command.bus', function (ServiceContainer $container) {
    return new RoutingCommandBus($container->get('command.service_router'));
});

$container->set('command.service_router', function (ServiceContainer $container) {
    return new ServiceAwareCommandRouter($container->get('command.service_map'));
});

$container->set('command.service_map', function (ServiceContainer $container) {
    $serviceMap = new ServiceAwareCommandMap($container);
    $serviceMap->registerHandlers($container['command.handlers']);

    return $serviceMap;
});

$container->set('command.handler.register_user', function () {
    return new RegisterUserHandler();
});

return $container;
