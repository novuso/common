<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Application\Messaging\Query\Routing;

use Novuso\Common\Application\Messaging\Query\Routing\QueryMap;
use Novuso\Common\Application\Messaging\Query\Routing\QueryRouter;
use Novuso\Common\Application\Messaging\Query\Routing\ServiceAwareQueryMap;
use Novuso\Common\Application\Messaging\Query\Routing\SimpleQueryRouter;
use Novuso\Common\Application\Messaging\Query\RoutingQueryBus;
use Novuso\Common\Application\Service\ServiceContainer;
use Novuso\Common\Domain\Messaging\Query\QueryBus;
use Novuso\Common\Test\Resources\Domain\Messaging\Query\UserByEmailHandler;
use Novuso\Common\Test\Resources\Domain\Messaging\Query\UserByEmailQuery;
use Novuso\System\Exception\AssertionException;
use Novuso\System\Exception\LookupException;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Messaging\Query\Routing\ServiceAwareQueryMap
 */
class ServiceAwareQueryMapTest extends UnitTestCase
{
    /** @var ServiceContainer */
    protected $container;

    protected function setUp(): void
    {
        $this->container = new ServiceContainer();
        $this->defineServices();
    }

    public function test_that_it_matches_the_correct_handler_instance()
    {
        $query = new UserByEmailQuery('jsmith@example.com');
        /** @var QueryBus $queryBus */
        $queryBus = $this->container->get(QueryBus::class);

        $user = $queryBus->fetch($query);

        static::assertSame('jsmith@example.com', $user['email']);
    }

    public function test_that_map_has_handler_returns_true_when_handler_registered()
    {
        /** @var QueryMap $queryMap */
        $queryMap = $this->container->get(QueryMap::class);

        static::assertTrue($queryMap->hasHandler(UserByEmailQuery::class));
    }

    public function test_that_map_has_handler_returns_false_when_handler_not_registered()
    {
        $this->container->setParameter('query.handlers', []);
        /** @var QueryMap $queryMap */
        $queryMap = $this->container->get(QueryMap::class);

        static::assertFalse($queryMap->hasHandler(UserByEmailQuery::class));
    }

    public function test_that_query_map_throws_exception_when_query_class_is_invalid()
    {
        $this->expectException(AssertionException::class);

        /** @var ServiceAwareQueryMap $queryMap */
        $queryMap = $this->container->get(QueryMap::class);
        $queryMap->registerHandler('FooBar', UserByEmailHandler::class);
    }

    public function test_that_query_map_throws_exception_when_handler_is_not_registered()
    {
        $this->expectException(LookupException::class);

        $this->container->setParameter('query.handlers', []);
        /** @var QueryMap $queryMap */
        $queryMap = $this->container->get(QueryMap::class);
        $queryMap->getHandler(UserByEmailQuery::class);
    }

    protected function defineServices()
    {
        $this->container->set(
            QueryBus::class,
            function (ServiceContainer $container) {
                return new RoutingQueryBus($container->get(QueryRouter::class));
            }
        );

        $this->container->set(
            QueryRouter::class,
            function (ServiceContainer $container) {
                return new SimpleQueryRouter($container->get(QueryMap::class));
            }
        );

        $this->container->set(
            QueryMap::class,
            function (ServiceContainer $container) {
                $queryMap = new ServiceAwareQueryMap($container);
                $queryMap->registerHandlers($container->getParameter('query.handlers'));

                return $queryMap;
            }
        );

        $this->container->set(
            UserByEmailHandler::class,
            function () {
                return new UserByEmailHandler();
            }
        );

        $this->container->setParameter(
            'query.handlers',
            [UserByEmailQuery::class => UserByEmailHandler::class]
        );
    }
}
