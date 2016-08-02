<?php declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Event;

use Novuso\Common\Domain\Messaging\Event\Event;
use Novuso\Common\Domain\Messaging\Event\EventDispatcher;
use Novuso\Common\Domain\Messaging\Event\EventSubscriber;

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
     * {@inheritdoc}
     */
    public function dispatch(Event $event)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function register(EventSubscriber $subscriber)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function unregister(EventSubscriber $subscriber)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function addHandler(string $eventType, callable $handler, int $priority = 0)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getHandlers(string $eventType = null): array
    {
    }

    /**
     * {@inheritdoc}
     */
    public function hasHandlers(string $eventType = null): bool
    {
    }

    /**
     * {@inheritdoc}
     */
    public function removeHandler(string $eventType, callable $handler)
    {
    }
}
