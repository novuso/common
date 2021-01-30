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
    public function process(QueryMessage $queryMessage, callable $next): void
    {
        $name = $queryMessage->payloadType()->toString();

        $this->logger->log(
            $this->logLevel,
            sprintf('Query received {%s}', $name),
            ['message' => $queryMessage->toArray()]
        );

        $next($queryMessage);

        $this->logger->log(
            $this->logLevel,
            sprintf('Query handled {%s}', $name),
            ['message' => $queryMessage->toArray()]
        );
    }
}
