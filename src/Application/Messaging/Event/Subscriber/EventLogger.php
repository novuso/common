<?php

declare(strict_types=1);

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
     * Constructs EventLogger
     */
    public function __construct(
        protected LoggerInterface $logger,
        protected string $logLevel = LogLevel::INFO
    ) {
    }

    /**
     * @inheritDoc
     */
    public static function eventRegistration(): array
    {
        return [AllEvents::class => 'logEvent'];
    }

    /**
     * Logs the event message
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
