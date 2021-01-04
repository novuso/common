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
    public function process(CommandMessage $message, callable $next): void
    {
        $name = $message->payloadType()->toString();

        $this->logger->log(
            $this->logLevel,
            sprintf('Command received {%s}', $name),
            ['message' => $message->toArray()]
        );

        $next($message);

        $this->logger->log(
            $this->logLevel,
            sprintf('Command handled {%s}', $name),
            ['message' => $message->toArray()]
        );
    }
}
