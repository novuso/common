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
     * Special event type for all events
     *
     * @var string
     */
    public const ALL_EVENTS = 'ALL_EVENTS';

    /**
     * Retrieves event registration
     *
     * The returned array keys are event types. The event type string is the
     * underscored version of the domain event class name.
     *
     * `Novuso\System\Utility\ClassName::underscore($className)` retrieves the
     * event type, when passed the fully-qualified class name of the event.
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
     * return ['domain_event.type' => 'methodName'];
     * </code>
     * <code>
     * return ['domain_event.type' => ['methodName', 10]];
     * </code>
     * <code>
     * return ['domain_event.type' => [['methodOne', 10], ['methodTwo']]];
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
