<?php declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Event\Subscriber;

use Novuso\Common\Domain\Messaging\Event\AllEvents;
use Novuso\Common\Domain\Messaging\Event\EventMessage;
use Novuso\Common\Domain\Messaging\Event\EventSubscriber;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * Class EventLogger
 */
final class EventLogger implements EventSubscriber
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
     * Constructs EventLogger
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
    public static function eventRegistration(): array
    {
        return [AllEvents::class => 'logEvent'];
    }

    /**
     * Logs the event message
     *
     * @param EventMessage $message The event message
     *
     * @return void
     */
    public function logEvent(EventMessage $message): void
    {
        $name = $message->payloadType()->toString();

        $this->logger->log(
            $this->logLevel,
            sprintf('Event dispatched {%s}', $name),
            ['message' => $message->toArray()]
        );
    }
}
