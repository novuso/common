<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Messaging\Event;

/**
 * EventSubscriber is the interface for an event subscriber
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface EventSubscriber
{
    /**
     * Retrieves event registration
     *
     * The returned array keys are event types. The event type string is the
     * fully qualified domain event class name.
     *
     * Array values can be:
     *
     * * The method name to call (default priority of 0)
     * * An array consisting of the method name to call and the priority
     * * An array of arrays consisting of the method names and priorities;
     *   unset priorities default to zero
     *
     * Examples:
     *
     * <code>
     * return [SomethingHappened::class => 'methodName'];
     * </code>
     * <code>
     * return [SomethingHappened::class => ['methodName', 10]];
     * </code>
     * <code>
     * return [SomethingHappened::class => [['methodOne', 10], ['methodTwo']]];
     * </code>
     *
     * Event handler signature:
     * <code>
     * function (EventMessage $message): void {}
     * </code>
     *
     * @return array
     */
    public static function eventRegistration(): array;
}
