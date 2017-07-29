<?php declare(strict_types=1);

namespace Novuso\Test\Common\Application\Logging;

use Monolog\Handler\TestHandler;
use Monolog\Logger;
use Novuso\Common\Application\Logging\SqlLogger;
use Novuso\Test\System\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Logging\SqlLogger
 */
class SqlLoggerTest extends UnitTestCase
{
    /** @var TestHandler */
    protected $logHandler;
    /** @var Logger */
    protected $logger;
    /** @var SqlLogger */
    protected $sqlLogger;

    protected function setUp()
    {
        parent::setUp();
        $this->logHandler = new TestHandler();
        $this->logger = new Logger('test');
        $this->logger->pushHandler($this->logHandler);
        $this->sqlLogger = new SqlLogger($this->logger);
    }

    public function test_that_logger_properly_removes_extra_whitespace()
    {
        $sql = "SELECT * FROM\nsome_table\n\nWHERE id =\t:id";
        $parameters = [':id' => ['value' => 123, 'type' => 'integer']];

        $this->sqlLogger->log($sql, $parameters);

        $this->assertTrue($this->logHandler->hasRecord(
            '[SQL]: SELECT * FROM some_table WHERE id = :id',
            Logger::DEBUG
        ));
    }
}
