<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Event;

use Novuso\Common\Domain\Messaging\Event\AllEvents;
use Novuso\Common\Domain\Messaging\Event\Event;
use Novuso\Common\Domain\Messaging\Event\EventMessage;
use Novuso\Common\Domain\Messaging\Event\EventSubscriber;
use Novuso\Common\Domain\Messaging\Event\SynchronousEventDispatcher;
use Novuso\System\Utility\ClassName;

/**
 * Class SimpleEventDispatcher
 */
class SimpleEventDispatcher implements SynchronousEventDispatcher
{
    protected array $handlers = [];
    protected array $sorted = [];

    /**
     * @inheritDoc
     */
    public function trigger(Event $event): void
    {
        $this->dispatch(EventMessage::create($event));
    }

    /**
     * @inheritDoc
     */
    public function dispatch(EventMessage $message): void
    {
        $eventType = ClassName::underscore($message->payload());
        $allEvents = ClassName::underscore(AllEvents::class);

        foreach ($this->getHandlers($eventType) as $handler) {
            call_user_func($handler, $message);
        }

        foreach ($this->getHandlers($allEvents) as $handler) {
            call_user_func($handler, $message);
        }
    }

    /**
     * @inheritDoc
     */
    public function register(EventSubscriber $subscriber): void
    {
        foreach ($subscriber->eventRegistration() as $eventType => $params) {
            $eventType = ClassName::underscore($eventType);
            if (is_string($params)) {
                $this->addHandler($eventType, [$subscriber, $params]);
            } elseif (is_string($params[0])) {
                $priority = isset($params[1]) ? (int) $params[1] : 0;
                $this->addHandler(
                    $eventType,
                    [$subscriber, $params[0]],
                    $priority
                );
            } else {
                foreach ($params as $handler) {
                    $priority = isset($handler[1]) ? (int) $handler[1] : 0;
                    $this->addHandler(
                        $eventType,
                        [$subscriber, $handler[0]],
                        $priority
                    );
                }
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function unregister(EventSubscriber $subscriber): void
    {
        foreach ($subscriber->eventRegistration() as $eventType => $params) {
            $eventType = ClassName::underscore($eventType);
            if (is_array($params) && is_array($params[0])) {
                foreach ($params as $handler) {
                    $this->removeHandler(
                        $eventType,
                        [$subscriber, $handler[0]]
                    );
                }
            } else {
                $handler = is_string($params) ? $params : $params[0];
                $this->removeHandler($eventType, [$subscriber, $handler]);
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function addHandler(
        string $eventType,
        callable $handler,
        int $priority = 0
    ): void {
        if (!isset($this->handlers[$eventType])) {
            $this->handlers[$eventType] = [];
        }

        if (!isset($this->handlers[$eventType][$priority])) {
            $this->handlers[$eventType][$priority] = [];
        }

        $this->handlers[$eventType][$priority][] = $handler;
        unset($this->sorted[$eventType]);
    }

    /**
     * @inheritDoc
     */
    public function getHandlers(?string $eventType = null): array
    {
        if ($eventType !== null) {
            if (!isset($this->handlers[$eventType])) {
                return [];
            }

            if (!isset($this->sorted[$eventType])) {
                $this->sortHandlers($eventType);
            }

            return $this->sorted[$eventType];
        }

        foreach (array_keys($this->handlers) as $eventType) {
            if (!isset($this->sorted[$eventType])) {
                $this->sortHandlers($eventType);
            }
        }

        return array_filter($this->sorted);
    }

    /**
     * @inheritDoc
     */
    public function hasHandlers(?string $eventType = null): bool
    {
        return (bool) count($this->getHandlers($eventType));
    }

    /**
     * @inheritDoc
     */
    public function removeHandler(string $eventType, callable $handler): void
    {
        if (!isset($this->handlers[$eventType])) {
            return;
        }

        foreach ($this->handlers[$eventType] as $priority => $handlers) {
            $key = array_search($handler, $handlers, true);
            if ($key !== false) {
                unset($this->handlers[$eventType][$priority][$key]);
                unset($this->sorted[$eventType]);
                if (empty($this->handlers[$eventType][$priority])) {
                    unset($this->handlers[$eventType][$priority]);
                }
                if (empty($this->handlers[$eventType])) {
                    unset($this->handlers[$eventType]);
                }
            }
        }
    }

    /**
     * Sorts event handlers by priority
     */
    protected function sortHandlers(string $eventType): void
    {
        $this->sorted[$eventType] = [];
        if (isset($this->handlers[$eventType])) {
            krsort($this->handlers[$eventType]);
            $this->sorted[$eventType] = call_user_func_array(
                'array_merge',
                $this->handlers[$eventType]
            );
        }
    }
}
