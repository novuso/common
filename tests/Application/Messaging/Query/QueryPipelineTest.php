<?php declare(strict_types=1);

namespace Novuso\Common\Test\Application\Messaging\Query;

use Monolog\Handler\TestHandler;
use Monolog\Logger;
use Novuso\Common\Application\Messaging\Query\Filter\QueryLogger;
use Novuso\Common\Application\Messaging\Query\QueryPipeline;
use Novuso\Common\Application\Messaging\Query\Routing\InMemoryQueryMap;
use Novuso\Common\Application\Messaging\Query\Routing\SimpleQueryRouter;
use Novuso\Common\Application\Messaging\Query\RoutingQueryBus;
use Novuso\Common\Test\Resources\Domain\Messaging\Query\UserByEmailHandler;
use Novuso\Common\Test\Resources\Domain\Messaging\Query\UserByEmailQuery;
use Novuso\System\Exception\AssertionException;
use Novuso\System\Exception\LookupException;
use Novuso\System\Test\TestCase\UnitTestCase;
use Novuso\System\Utility\ClassName;

/**
 * @covers \Novuso\Common\Application\Messaging\Query\Filter\QueryLogger
 * @covers \Novuso\Common\Application\Messaging\Query\Routing\InMemoryQueryMap
 * @covers \Novuso\Common\Application\Messaging\Query\Routing\SimpleQueryRouter
 * @covers \Novuso\Common\Application\Messaging\Query\QueryPipeline
 * @covers \Novuso\Common\Application\Messaging\Query\RoutingQueryBus
 */
class QueryPipelineTest extends UnitTestCase
{
    /** @var QueryPipeline */
    protected $pipeline;
    /** @var RoutingQueryBus */
    protected $queryBus;
    /** @var SimpleQueryRouter */
    protected $queryRouter;
    /** @var InMemoryQueryMap */
    protected $queryMap;
    /** @var QueryLogger */
    protected $queryLogger;
    /** @var TestHandler */
    protected $logHandler;
    /** @var Logger */
    protected $logger;

    protected function setUp(): void
    {
        $this->logHandler = new TestHandler();
        $this->logger = new Logger('test');
        $this->logger->pushHandler($this->logHandler);
        $this->queryLogger = new QueryLogger($this->logger);
        $this->queryMap = new InMemoryQueryMap();
        $this->queryRouter = new SimpleQueryRouter($this->queryMap);
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

    public function test_that_query_map_throws_exception_when_query_class_is_invalid()
    {
        $this->expectException(AssertionException::class);

        $handler = new UserByEmailHandler();
        $this->queryMap->registerHandler('FooBar', $handler);
    }

    public function test_that_query_map_throws_exception_when_handler_is_not_registered()
    {
        $this->expectException(LookupException::class);

        $query = new UserByEmailQuery('jsmith@example.com');
        $this->pipeline->fetch($query);
    }
}
