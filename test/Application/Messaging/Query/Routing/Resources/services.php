<?php

use Novuso\Common\Adapter\Service\ServiceContainer;
use Novuso\Common\Application\Messaging\Query\Routing\ServiceAwareQueryMap;
use Novuso\Common\Application\Messaging\Query\Routing\ServiceAwareQueryRouter;
use Novuso\Common\Application\Messaging\Query\RoutingQueryBus;
use Novuso\Test\Common\Resources\Domain\Messaging\Query\UserByEmailHandler;
use Novuso\Test\Common\Resources\Domain\Messaging\Query\UserByEmailQuery;

$container = new ServiceContainer();

$container['query.handlers'] = [
    UserByEmailQuery::class => 'query.handler.user_by_email'
];

$container->set('query.bus', function (ServiceContainer $container) {
    return new RoutingQueryBus($container->get('query.service_router'));
});

$container->set('query.service_router', function (ServiceContainer $container) {
    return new ServiceAwareQueryRouter($container->get('query.service_map'));
});

$container->set('query.service_map', function (ServiceContainer $container) {
    $serviceMap = new ServiceAwareQueryMap($container);
    $serviceMap->registerHandlers($container['query.handlers']);

    return $serviceMap;
});

$container->set('query.handler.user_by_email', function () {
    return new UserByEmailHandler();
});

return $container;
