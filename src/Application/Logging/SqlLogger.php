<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Logging;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * Class SqlLogger
 */
final class SqlLogger
{
    /**
     * Constructs SqlLogger
     */
    public function __construct(
        protected LoggerInterface $logger,
        protected string $logLevel = LogLevel::DEBUG
    ) {
    }

    /**
     * Logs SQL and parameters
     */
    public function log(string $sql, array $parameters = []): void
    {
        $message = sprintf('[SQL]: %s', $this->removeWhitespace($sql));
        $this->logger->log($this->logLevel, $message, $parameters);
    }

    /**
     * Removes additional whitespace from SQL
     */
    protected function removeWhitespace(string $sql): string
    {
        return preg_replace('/[\s\t\n\r]+/', ' ', trim($sql));
    }
}
