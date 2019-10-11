<?php declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Query\Filter;

use Novuso\Common\Domain\Messaging\Query\QueryFilter;
use Novuso\Common\Domain\Messaging\Query\QueryMessage;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * Class QueryLogger
 */
final class QueryLogger implements QueryFilter
{
    /**
     * Logger
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Log level
     *
     * @var string
     */
    protected $logLevel;

    /**
     * Constructs QueryLogger
     *
     * @param LoggerInterface $logger   The logger
     * @param string          $logLevel The log level
     */
    public function __construct(LoggerInterface $logger, string $logLevel = LogLevel::INFO)
    {
        $this->logger = $logger;
        $this->logLevel = $logLevel;
    }

    /**
     * {@inheritdoc}
     */
    public function process(QueryMessage $message, callable $next): void
    {
        $name = $message->payloadType()->toString();

        $this->logger->log(
            $this->logLevel,
            sprintf('Query received {%s}', $name),
            ['message' => $message->toArray()]
        );

        $next($message);

        $this->logger->log(
            $this->logLevel,
            sprintf('Query handled {%s}', $name),
            ['message' => $message->toArray()]
        );
    }
}
