<?php
/**
 * This file is part of the Novuso Framework
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */

namespace Novuso\Common\Functions;

use Closure;
use DateTimeInterface;
use Novuso\Common\Domain\DateTime\Date;
use Novuso\Common\Domain\DateTime\DateTime;
use Novuso\Common\Domain\DateTime\Time;
use Novuso\Common\Domain\Identification\Uuid;
use Novuso\Common\Domain\Type\CString;
use Novuso\Common\Domain\Type\MbString;
use Novuso\System\Collection\ArrayCollection;
use Novuso\System\Collection\ArrayList;
use Novuso\System\Collection\HashSet;
use Novuso\System\Collection\HashTable;
use Novuso\System\Collection\LinkedDeque;
use Novuso\System\Collection\LinkedQueue;
use Novuso\System\Collection\LinkedStack;
use Novuso\System\Exception\DomainException;
use Novuso\System\Exception\RuntimeException;
use Novuso\System\Type\Arrayable;
use Novuso\System\Type\Type;
use Novuso\System\Utility\FastHasher;
use Novuso\System\Utility\Validate;
use Novuso\System\Utility\VarPrinter;
use Traversable;

/**
 * Binds an anonymous function to an object scope
 *
 * @param Closure $closure The anonymous function
 * @param object  $object  The scope instance
 *
 * @return Closure
 *
 * @throws DomainException When $object is not valid
 * @throws RuntimeException When bind operation fails
 */
function bind(Closure $closure, $object): Closure
{
    if (!is_object($object)) {
        $message = sprintf('bind expects $object to be an object; received %s', var_print($object));
        throw new DomainException($message);
    }

    $function = Closure::bind($closure, $object, get_class($object));

    if ($function === false) {
        $message = sprintf('failed to bind %s to closure', var_print($object));
        throw new RuntimeException($message);
    }

    return $function;
}

/**
 * Creates an ArrayCollection instance
 *
 * @param Arrayable|Traversable|array $items The items to collect
 *
 * @return ArrayCollection
 *
 * @throws DomainException When the items are not valid
 */
function collect($items = []): ArrayCollection
{
    if (!is_array($items)) {
        if ($items instanceof Arrayable) {
            $items = $items->toArray();
        } elseif ($items instanceof Traversable) {
            $items = iterator_to_array($items);
        } else {
            $message = sprintf('Invalid items: %s', var_print($items));
            throw new DomainException($message);
        }
    }

    return ArrayCollection::create($items);
}

/**
 * Creates Date instance from date values
 *
 * @param int $year  The year
 * @param int $month The month
 * @param int $day   The day
 *
 * @return Date
 *
 * @throws DomainException When the date is not valid
 */
function date_create(int $year, int $month, int $day): Date
{
    return Date::create($year, $month, $day);
}

/**
 * Creates Date instance for the current date
 *
 * @param string|null $timezone The timezone string or null for default
 *
 * @return Date
 */
function date_now(?string $timezone = null): Date
{
    return Date::now($timezone);
}

/**
 * Creates DateTime instance from date and time values
 *
 * @param int         $year     The year
 * @param int         $month    The month
 * @param int         $day      The day
 * @param int         $hour     The hour
 * @param int         $minute   The minute
 * @param int         $second   The second
 * @param string|null $timezone The timezone string or null for default
 *
 * @return DateTime
 *
 * @throws DomainException When the date/time is not valid
 */
function date_time_create(
    int $year,
    int $month,
    int $day,
    int $hour,
    int $minute,
    int $second,
    ?string $timezone = null
): DateTime {
    return DateTime::create($year, $month, $day, $hour, $minute, $second, $timezone);
}

/**
 * Creates DateTime instance from a native DateTime
 *
 * @param DateTimeInterface $dateTime A DateTimeInterface instance
 *
 * @return DateTime
 */
function date_time_from_native(DateTimeInterface $dateTime): DateTime
{
    return DateTime::fromNative($dateTime);
}

/**
 * Creates DateTime instance from a date/time string
 *
 * @param string $value The date/time string
 *
 * @return DateTime
 *
 * @throws DomainException When the date/time is not formatted correctly
 * @throws DomainException When the date/time is invalid
 */
function date_time_from_string(string $value): DateTime
{
    return DateTime::fromString($value);
}

/**
 * Creates DateTime instance from a timestamp and timezone
 *
 * @param int         $timestamp The timestamp
 * @param string|null $timezone  The timezone string or null for default
 *
 * @return DateTime
 */
function date_time_from_timestamp(int $timestamp, ?string $timezone = null): DateTime
{
    return DateTime::fromTimestamp($timestamp, $timezone);
}

/**
 * Creates DateTime instance for the current date and time
 *
 * @param string|null $timezone The timezone string or null for default
 *
 * @return DateTime
 */
function date_time_now(?string $timezone = null): DateTime
{
    return DateTime::now($timezone);
}

/**
 * Creates a LinkedDeque instance
 *
 * If a type is not provided, the item type is dynamic.
 *
 * The type can be any fully-qualified class or interface name,
 * or one of the following type strings:
 * [array, object, bool, int, float, string, callable]
 *
 * @param string|null $itemType The item type
 *
 * @return LinkedDeque
 */
function deque(?string $itemType = null): LinkedDeque
{
    return LinkedDeque::of($itemType);
}

/**
 * Creates a string hash for a value
 *
 * @param mixed  $value     The value
 * @param string $algorithm The hash algorithm
 *
 * @return string
 */
function fast_hash($value, string $algorithm = 'fnv1a32'): string
{
    return FastHasher::hash($value, $algorithm);
}

/**
 * Creates a HashTable instance
 *
 * If types are not provided, the types are dynamic.
 *
 * The type can be any fully-qualified class or interface name,
 * or one of the following type strings:
 * [array, object, bool, int, float, string, callable]
 *
 * @param string|null $keyType   The key type
 * @param string|null $valueType The value type
 *
 * @return HashTable
 */
function hash_table(?string $keyType = null, ?string $valueType = null): HashTable
{
    return HashTable::of($keyType, $valueType);
}

/**
 * Creates an ArrayList instance
 *
 * If a type is not provided, the item type is dynamic.
 *
 * The type can be any fully-qualified class or interface name,
 * or one of the following type strings:
 * [array, object, bool, int, float, string, callable]
 *
 * @param string|null $itemType The item type
 *
 * @return ArrayList
 */
function list_of(?string $itemType = null): ArrayList
{
    return ArrayList::of($itemType);
}

/**
 * Creates MbString instance
 *
 * @param mixed $value The string value
 *
 * @return MbString
 *
 * @throws DomainException When the value is not valid
 */
function mb_string($value): MbString
{
    if (!Validate::isStringCastable($value)) {
        $message = sprintf('Invalid string value: %s', var_print($value));
        throw new DomainException($message);
    }

    if ($value instanceof MbString) {
        return $value;
    }

    /** @var MbString $string */
    $string = MbString::create((string) $value);

    return $string;
}

/**
 * Creates a LinkedQueue instance
 *
 * If a type is not provided, the item type is dynamic.
 *
 * The type can be any fully-qualified class or interface name,
 * or one of the following type strings:
 * [array, object, bool, int, float, string, callable]
 *
 * @param string|null $itemType The item type
 *
 * @return LinkedQueue
 */
function queue(?string $itemType = null): LinkedQueue
{
    return LinkedQueue::of($itemType);
}

/**
 * Checks if two values are the same type
 *
 * @param mixed $value1 The first value
 * @param mixed $value2 The second value
 *
 * @return bool
 */
function same_type($value1, $value2): bool
{
    return Validate::areSameType($value1, $value2);
}

/**
 * Creates a HashSet instance
 *
 * If a type is not provided, the item type is dynamic.
 *
 * The type can be any fully-qualified class or interface name,
 * or one of the following type strings:
 * [array, object, bool, int, float, string, callable]
 *
 * @param string|null $itemType The item type
 *
 * @return HashSet
 */
function set(?string $itemType = null): HashSet
{
    return HashSet::of($itemType);
}

/**
 * Creates a LinkedStack instance
 *
 * If a type is not provided, the item type is dynamic.
 *
 * The type can be any fully-qualified class or interface name,
 * or one of the following type strings:
 * [array, object, bool, int, float, string, callable]
 *
 * @param string|null $itemType The item type
 *
 * @return LinkedStack
 */
function stack(?string $itemType = null): LinkedStack
{
    return LinkedStack::of($itemType);
}

/**
 * Creates CString instance
 *
 * @param mixed $value The string value
 *
 * @return CString
 *
 * @throws DomainException When the value is not valid
 */
function string($value): CString
{
    if (!Validate::isStringCastable($value)) {
        $message = sprintf('Invalid string value: %s', var_print($value));
        throw new DomainException($message);
    }

    if ($value instanceof CString) {
        return $value;
    }

    /** @var CString $string */
    $string = CString::create((string) $value);

    return $string;
}

/**
 * Creates Time instance from time values
 *
 * @param int $hour   The hour
 * @param int $minute The minute
 * @param int $second The second
 *
 * @return Time
 *
 * @throws DomainException When the time is not valid
 */
function time_create(int $hour, int $minute, int $second): Time
{
    return Time::create($hour, $minute, $second);
}

/**
 * Creates Time instance for the current time
 *
 * @param string|null $timezone The timezone string or null for default
 *
 * @return Time
 */
function time_now(?string $timezone = null): Time
{
    return Time::now($timezone);
}

/**
 * Creates Type instance from an object or class name
 *
 * @param object|string $object An object, fully qualified class name, or
 *                              canonical class name
 *
 * @return Type
 */
function type($object): Type
{
    return Type::create($object);
}

/**
 * Creates a sequential pseudo-random Uuid instance
 *
 * @param bool $msb Whether or not timestamp covers most significant bits
 *
 * @return Uuid
 */
function uuid(bool $msb = true): Uuid
{
    return Uuid::comb($msb);
}

/**
 * Reads a string representation from a value
 *
 * @param mixed $value The value
 *
 * @return string
 */
function var_print($value): string
{
    return VarPrinter::toString($value);
}
