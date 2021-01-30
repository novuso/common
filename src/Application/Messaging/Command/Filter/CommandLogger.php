<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Command\Filter;

use Novuso\Common\Domain\Messaging\Command\CommandFilter;
use Novuso\Common\Domain\Messaging\Command\CommandMessage;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * Class CommandLogger
 */
final class CommandLogger implements CommandFilter
{
    /**
     * Constructs CommandLogger
     */
    public function __construct(
        protected LoggerInterface $logger,
        protected string $logLevel = LogLevel::INFO
    ) {
    }

    /**
     * @inheritDoc
     */
    public function process(CommandMessage $commandMessage, callable $next): void
    {
        $name = $commandMessage->payloadType()->toString();

        $this->logger->log(
            $this->logLevel,
            sprintf('Command received {%s}', $name),
            ['message' => $commandMessage->toArray()]
        );

        $next($commandMessage);

        $this->logger->log(
            $this->logLevel,
            sprintf('Command handled {%s}', $name),
            ['message' => $commandMessage->toArray()]
        );
    }
}
