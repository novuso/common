<?php declare(strict_types=1);

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
     * Constructs CommandLogger
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
