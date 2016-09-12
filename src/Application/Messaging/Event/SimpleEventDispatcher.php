<?php declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Event;

use Novuso\Common\Domain\Messaging\Event\Event;
use Novuso\Common\Domain\Messaging\Event\EventDispatcher;
use Novuso\Common\Domain\Messaging\Event\EventMessage;
use Novuso\Common\Domain\Messaging\Event\EventSubscriber;
use Novuso\Common\Domain\Model\Api\AggregateRoot;
use Novuso\System\Utility\ClassName;

/**
 * SimpleEventDispatcher is a synchronous event dispatcher
 *
 * @copyright Copyright (c) 2016, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class SimpleEventDispatcher implements EventDispatcher
{
    /**
     * Event handlers
     *
     * @var array
     */
    protected $handlers = [];

    /**
     * Sorted handlers
     *
     * @var array
     */
    protected $sorted = [];

    /**
     * {@inheritdoc}
     */
    public function dispatch(Event $event)
    {
        $message = EventMessage::create($event);
        $eventType = ClassName::underscore($event);

        foreach ($this->getHandlers($eventType) as $handler) {
            call_user_func($handler, $message);
        }

        foreach ($this->getHandlers(EventSubscriber::ALL_EVENTS) as $handler) {
            call_user_func($handler, $message);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function dispatchEvents(AggregateRoot $aggregateRoot)
    {
        foreach ($aggregateRoot->extractRecordedEvents() as $event) {
            $this->dispatch($event);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function register(EventSubscriber $subscriber)
    {
        foreach ($subscriber->eventRegistration() as $eventType => $params) {
            if (is_string($params)) {
                $this->addHandler($eventType, [$subscriber, $params]);
            } elseif (is_string($params[0])) {
                $priority = isset($params[1]) ? (int) $params[1] : 0;
                $this->addHandler($eventType, [$subscriber, $params[0]], $priority);
            } else {
                foreach ($params as $handler) {
                    $priority = isset($handler[1]) ? (int) $handler[1] : 0;
                    $this->addHandler($eventType, [$subscriber, $handler[0]], $priority);
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function unregister(EventSubscriber $subscriber)
    {
        foreach ($subscriber->eventRegistration() as $eventType => $params) {
            if (is_array($params) && is_array($params[0])) {
                foreach ($params as $handler) {
                    $this->removeHandler($eventType, [$subscriber, $handler[0]]);
                }
            } else {
                $handler = is_string($params) ? $params : $params[0];
                $this->removeHandler($eventType, [$subscriber, $handler]);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addHandler(string $eventType, callable $handler, int $priority = 0)
    {
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
     * {@inheritdoc}
     */
    public function getHandlers(string $eventType = null): array
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
     * {@inheritdoc}
     */
    public function hasHandlers(string $eventType = null): bool
    {
        return (bool) count($this->getHandlers($eventType));
    }

    /**
     * {@inheritdoc}
     */
    public function removeHandler(string $eventType, callable $handler)
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
     *
     * @param string $eventType The event type
     *
     * @return void
     */
    protected function sortHandlers(string $eventType)
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
