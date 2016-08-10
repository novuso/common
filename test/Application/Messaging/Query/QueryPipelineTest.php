<?php

namespace Novuso\Test\Common\Application\Messaging\Query;

use Monolog\Handler\TestHandler;
use Monolog\Logger;
use Novuso\Common\Application\Messaging\Query\Filter\QueryLogger;
use Novuso\Common\Application\Messaging\Query\QueryPipeline;
use Novuso\Common\Application\Messaging\Query\Routing\InMemoryQueryMap;
use Novuso\Common\Application\Messaging\Query\Routing\InMemoryQueryRouter;
use Novuso\Common\Application\Messaging\Query\RoutingQueryBus;
use Novuso\System\Utility\ClassName;
use Novuso\Test\Common\Resources\Domain\Messaging\Query\UserByEmailHandler;
use Novuso\Test\Common\Resources\Domain\Messaging\Query\UserByEmailQuery;
use Novuso\Test\System\TestCase\UnitTestCase;

/**
 * @covers Novuso\Common\Application\Messaging\Query\Filter\QueryLogger
 * @covers Novuso\Common\Application\Messaging\Query\Routing\InMemoryQueryMap
 * @covers Novuso\Common\Application\Messaging\Query\Routing\InMemoryQueryRouter
 * @covers Novuso\Common\Application\Messaging\Query\QueryPipeline
 * @covers Novuso\Common\Application\Messaging\Query\RoutingQueryBus
 */
class QueryPipelineTest extends UnitTestCase
{
    /** @var QueryPipeline */
    protected $pipeline;
    /** @var RoutingQueryBus */
    protected $queryBus;
    /** @var InMemoryQueryRouter */
    protected $queryRouter;
    /** @var InMemoryQueryMap */
    protected $queryMap;
    /** @var QueryLogger */
    protected $queryLogger;
    /** @var TestHandler */
    protected $logHandler;
    /** @var Logger */
    protected $logger;

    protected function setUp()
    {
        $this->logHandler = new TestHandler();
        $this->logger = new Logger('test');
        $this->logger->pushHandler($this->logHandler);
        $this->queryLogger = new QueryLogger($this->logger);
        $this->queryMap = new InMemoryQueryMap();
        $this->queryRouter = new InMemoryQueryRouter($this->queryMap);
        $this->queryBus = new RoutingQueryBus($this->queryRouter);
        $this->pipeline = new QueryPipeline($this->queryBus);
        $this->pipeline->addFilter($this->queryLogger);
    }

    public function test_that_query_is_handled_by_pipeline()
    {
        $handler = new UserByEmailHandler();
        $this->queryMap->registerHandlers([UserByEmailQuery::class => $handler]);
        $query = new UserByEmailQuery('jsmith@example.com');
        $user = $this->pipeline->fetch($query);
        $this->assertTrue(
            $this->queryMap->hasHandler(UserByEmailQuery::class)
            && $this->logHandler->hasInfoThatContains(sprintf(
                'Query received {%s}',
                ClassName::canonical(UserByEmailQuery::class)
            ))
            && $this->logHandler->hasInfoThatContains(sprintf(
                'Query handled {%s}',
                ClassName::canonical(UserByEmailQuery::class)
            ))
            && $user['email'] === 'jsmith@example.com'
        );
    }

    /**
     * @expectedException \Novuso\System\Exception\DomainException
     */
    public function test_that_query_map_throws_exception_when_query_class_is_invalid()
    {
        $handler = new UserByEmailHandler();
        $this->queryMap->registerHandler('FooBar', $handler);
    }

    /**
     * @expectedException \Novuso\System\Exception\LookupException
     */
    public function test_that_query_map_throws_exception_when_handler_is_not_registered()
    {
        $query = new UserByEmailQuery('jsmith@example.com');
        $this->pipeline->fetch($query);
    }
}
