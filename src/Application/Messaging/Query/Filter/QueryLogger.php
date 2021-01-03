<?php

declare(strict_types=1);

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
     * Constructs QueryLogger
     */
    public function __construct(
        protected LoggerInterface $logger,
        protected string $logLevel = LogLevel::INFO
    ) {
    }

    /**
     * @inheritDoc
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
