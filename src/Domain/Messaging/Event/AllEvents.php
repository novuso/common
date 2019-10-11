<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Messaging\Event;

/**
 * Class AllEvents
 *
 * This class exists so an event subscriber can register listeners for "all events".
 * Simply use this class name in an event subscriber in place of an event class.
 */
final class AllEvents
{
}
