<?php

namespace Novuso\Test\Common\Application\Messaging\Query\Routing;

use Novuso\Common\Application\Service\ServiceContainer;
use Novuso\Common\Domain\Messaging\Query\QueryBus;
use Novuso\Test\Common\Resources\Domain\Messaging\Query\UserByEmailQuery;
use Novuso\Test\System\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Messaging\Query\Routing\ServiceAwareQueryMap
 * @covers \Novuso\Common\Application\Messaging\Query\Routing\ServiceAwareQueryRouter
 */
class ServiceAwareQueryRouterTest extends UnitTestCase
{
    /**
     * @var ServiceContainer
     */
    protected $container;

    protected function setUp()
    {
        $this->container = require __DIR__.'/Resources/services.php';
    }

    public function test_that_it_matches_the_correct_handler_instance()
    {
        $query = new UserByEmailQuery('jsmith@example.com');
        /** @var QueryBus $queryBus */
        $queryBus = $this->container->get('query.bus');
        $user = $queryBus->fetch($query);
        $this->assertSame('jsmith@example.com', $user['email']);
    }

    public function test_that_map_has_handler_returns_true_when_handler_registered()
    {
        $queryMap = $this->container->get('query.service_map');
        $this->assertTrue($queryMap->hasHandler(UserByEmailQuery::class));
    }

    public function test_that_map_has_handler_returns_false_when_handler_not_registered()
    {
        $this->container['query.handlers'] = [];
        $queryMap = $this->container->get('query.service_map');
        $this->assertFalse($queryMap->hasHandler(UserByEmailQuery::class));
    }

    /**
     * @expectedException \Novuso\System\Exception\DomainException
     */
    public function test_that_query_map_throws_exception_when_query_class_is_invalid()
    {
        $queryMap = $this->container->get('query.service_map');
        $queryMap->registerHandler('FooBar', 'some.service');
    }

    /**
     * @expectedException \Novuso\System\Exception\LookupException
     */
    public function test_that_query_map_throws_exception_when_handler_is_not_registered()
    {
        $this->container['query.handlers'] = [];
        $queryMap = $this->container->get('query.service_map');
        $queryMap->getHandler(UserByEmailQuery::class);
    }
}
