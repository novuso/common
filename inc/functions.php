<?php

declare(strict_types=1);

namespace Novuso\Common;

use DateTimeInterface;
use Novuso\Common\Domain\Repository\Pagination;
use Novuso\Common\Domain\Repository\ResultSet;
use Novuso\Common\Domain\Type\JsonObject;
use Novuso\Common\Domain\Type\MbStringObject;
use Novuso\Common\Domain\Type\StringObject;
use Novuso\Common\Domain\Value\Communication\EmailAddress;
use Novuso\Common\Domain\Value\DateTime\Date;
use Novuso\Common\Domain\Value\DateTime\DateRange;
use Novuso\Common\Domain\Value\DateTime\DateTime;
use Novuso\Common\Domain\Value\DateTime\Time;
use Novuso\Common\Domain\Value\DateTime\Timezone;
use Novuso\Common\Domain\Value\Identifier\Uri;
use Novuso\Common\Domain\Value\Identifier\Url;
use Novuso\Common\Domain\Value\Identifier\Uuid;
use Novuso\System\Collection\ArrayList;
use Novuso\System\Collection\HashSet;
use Novuso\System\Collection\HashTable;
use Novuso\System\Collection\Iterator\GeneratorIterator;
use Novuso\System\Collection\LinkedDeque;
use Novuso\System\Collection\LinkedQueue;
use Novuso\System\Collection\LinkedStack;
use Novuso\System\Exception\DomainException;
use Novuso\System\Exception\RuntimeException;
use Novuso\System\Type\Arrayable;
use Novuso\System\Type\Type;
use Novuso\System\Utility\Environment;
use Novuso\System\Utility\VarPrinter;
use ReflectionException;

/**
 * Creates an ArrayList instance
 *
 * If a type is not provided, the item type is dynamic.
 *
 * The type can be any fully-qualified class or interface name,
 * or one of the following type strings:
 * [array, object, bool, int, float, string, callable]
 */
function array_list(
    iterable $items = [],
    ?string $itemType = null
): ArrayList {
    $list = ArrayList::of($itemType);

    foreach ($items as $item) {
        $list->add($item);
    }

    return $list;
}

/**
 * Encodes data with URL safe base64 encoding
 *
 * @link https://brockallen.com/2014/10/17/base64url-encoding/ base64url
 */
function base64url_encode(string $data): string
{
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

/**
 * Decodes data encoded with URL safe base64 encoding
 *
 * @link https://brockallen.com/2014/10/17/base64url-encoding/ base64url
 */
function base64url_decode(string $data): string
{
    return base64_decode(strtr($data, '-_', '+/'));
}

/**
 * Creates a Date instance from date values
 *
 * @throws DomainException When the date is not valid
 */
function date_create(int $year, int $month, int $day): Date
{
    return Date::create($year, $month, $day);
}

/**
 * Creates a Date instance from a native DateTime
 */
function date_from_native(DateTimeInterface $dateTime): Date
{
    return Date::fromNative($dateTime);
}

/**
 * Creates a Date instance from a string representation
 *
 * @throws DomainException When the value is invalid
 */
function date_from_string(string $value): Date
{
    return Date::fromString($value);
}

/**
 * Creates a Date instance from a timestamp and timezone
 */
function date_from_timestamp(int $timestamp, ?string $timezone = null): Date
{
    return Date::fromTimestamp($timestamp, $timezone);
}

/**
 * Creates a Date instance for the current date
 */
function date_now(?string $timezone = null): Date
{
    return Date::now($timezone);
}

/**
 * Creates a DateRange instance from a start date, end date, and interval step
 *
 * The step may be positive or negative, but cannot be zero.
 *
 * @throws DomainException When the arguments are invalid
 */
function date_range_create(Date $start, Date $end, int $step = 1): DateRange
{
    return DateRange::create($start, $end, $step);
}

/**
 * Creates a DateRange instance from a start date, interval step, and iterations
 *
 * @throws DomainException When the arguments are invalid
 */
function date_range_from_iterations(
    Date $start,
    int $step,
    int $iterations
): DateRange {
    return DateRange::fromIterations($start, $step, $iterations);
}

/**
 * Creates a DateRange instance from a string representation
 *
 * @throws DomainException When the value is invalid
 */
function date_range_from_string(string $value): DateRange
{
    return DateRange::fromString($value);
}

/**
 * Creates a DateTime instance from date and time values
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
    int $microsecond = 0,
    ?string $timezone = null
): DateTime {
    return DateTime::create(
        $year,
        $month,
        $day,
        $hour,
        $minute,
        $second,
        $microsecond,
        $timezone
    );
}

/**
 * Creates a DateTime instance for the current date and time
 */
function date_time_now(?string $timezone = null): DateTime
{
    return DateTime::now($timezone);
}

/**
 * Creates a DateTime instance from date/time string in a given format
 *
 * @throws DomainException When the date/time or timezone is not valid
 */
function date_time_from_format(
    string $format,
    string $time,
    ?string $timezone = null
): DateTime {
    return DateTime::fromFormat($format, $time, $timezone);
}

/**
 * Creates a DateTime instance from a native DateTime
 *
 * @throws DomainException When the timezone is not valid
 */
function date_time_from_native(DateTimeInterface $dateTime): DateTime
{
    return DateTime::fromNative($dateTime);
}

/**
 * Creates a DateTime instance from a timestamp and timezone
 *
 * @throws DomainException When the date/time or timezone is not valid
 */
function date_time_from_timestamp(
    int $timestamp,
    ?string $timezone = null
): DateTime {
    return DateTime::fromTimestamp($timestamp, $timezone);
}

/**
 * Creates a DateTime instance from a string representation
 *
 * @throws DomainException When the value is invalid
 */
function date_time_from_string(string $value): DateTime
{
    return DateTime::fromString($value);
}

/**
 * Creates an EmailAddress instance
 *
 * @throws DomainException When the email address is invalid
 */
function email_address(string $emailAddress): EmailAddress
{
    return EmailAddress::fromString($emailAddress);
}

/**
 * Retrieves the value of an environment variable
 */
function env(
    string $key,
    string|bool|null $default = null
): string|bool|null {
    return Environment::get($key, $default);
}

/**
 * Creates a GeneratorIterator instance
 *
 * @throws DomainException When function is not a generator
 * @throws ReflectionException
 */
function generator(callable $function, array $args = []): GeneratorIterator
{
    return new GeneratorIterator($function, $args);
}

/**
 * Creates a HashSet instance
 *
 * If a type is not provided, the item type is dynamic.
 *
 * The type can be any fully-qualified class or interface name,
 * or one of the following type strings:
 * [array, object, bool, int, float, string, callable]
 */
function hash_set(iterable $items = [], ?string $itemType = null): HashSet
{
    $set = HashSet::of($itemType);

    foreach ($items as $item) {
        $set->add($item);
    }

    return $set;
}

/**
 * Creates a HashTable instance
 *
 * If types are not provided, the types are dynamic.
 *
 * The types can be any fully-qualified class or interface name,
 * or one of the following type strings:
 * [array, object, bool, int, float, string, callable]
 */
function hash_table(
    iterable $items = [],
    ?string $keyType = null,
    ?string $valueType = null
): HashTable {
    $table = HashTable::of($keyType, $valueType);

    foreach ($items as $key => $value) {
        $table->set($key, $value);
    }

    return $table;
}

/**
 * Creates JsonObject instance from data
 *
 * @throws DomainException When data is not JSON encodable
 */
function json_data(mixed $data, ?int $encodingOptions = null): JsonObject
{
    if ($encodingOptions === null) {
        return JsonObject::fromData($data);
    }

    return JsonObject::fromData($data, $encodingOptions);
}

/**
 * Creates JsonObject instance from JSON string
 *
 * @throws DomainException When JSON string is invalid
 */
function json_string(string $json): JsonObject
{
    return JsonObject::fromString($json);
}

/**
 * Creates a LinkedDeque instance
 *
 * If a type is not provided, the item type is dynamic.
 *
 * The type can be any fully-qualified class or interface name,
 * or one of the following type strings:
 * [array, object, bool, int, float, string, callable]
 */
function linked_deque(
    iterable $items = [],
    ?string $itemType = null
): LinkedDeque {
    $deque = LinkedDeque::of($itemType);

    foreach ($items as $item) {
        $deque->addLast($item);
    }

    return $deque;
}

/**
 * Creates a LinkedQueue instance
 *
 * If a type is not provided, the item type is dynamic.
 *
 * The type can be any fully-qualified class or interface name,
 * or one of the following type strings:
 * [array, object, bool, int, float, string, callable]
 */
function linked_queue(
    iterable $items = [],
    ?string $itemType = null
): LinkedQueue {
    $queue = LinkedQueue::of($itemType);

    foreach ($items as $item) {
        $queue->enqueue($item);
    }

    return $queue;
}

/**
 * Creates a LinkedStack instance
 *
 * If a type is not provided, the item type is dynamic.
 *
 * The type can be any fully-qualified class or interface name,
 * or one of the following type strings:
 * [array, object, bool, int, float, string, callable]
 */
function linked_stack(
    iterable $items = [],
    ?string $itemType = null
): LinkedStack {
    $stack = LinkedStack::of($itemType);

    foreach ($items as $item) {
        $stack->push($item);
    }

    return $stack;
}

/**
 * Creates MbStringObject instance
 */
function mb_string(string $value): MbStringObject
{
    return MbStringObject::create($value);
}

/**
 * Creates Pagination instance
 */
function pagination(
    int $page = Pagination::DEFAULT_PAGE,
    int $perPage = Pagination::DEFAULT_PER_PAGE,
    array $orderings = []
): Pagination {
    return new Pagination($page, $perPage, $orderings);
}

/**
 * Reads a CSV file
 *
 * @throws DomainException When arguments are not valid
 * @throws RuntimeException When the file cannot be read
 */
function read_csv(
    string $path,
    int $skipLines = 0,
    Arrayable|array|null $headers = null
): iterable {
    $fp = @fopen($path, 'r');

    if ($fp === false) {
        $message = sprintf('Unable to open file for reading: %s', $path);
        throw new RuntimeException($message);
    }

    if ($headers !== null) {
        if ($headers instanceof Arrayable) {
            $headers = $headers->toArray();
        }
        if (!is_array($headers)) {
            $message = sprintf(
                'write_csv expects $headers to be an array or %s',
                Arrayable::class
            );
            throw new DomainException($message);
        }
    }

    $count = 0;
    while ($fields = fgetcsv($fp)) {
        $count++;
        if ($skipLines >= $count) {
            continue;
        }
        if ($headers === null) {
            yield $fields;
            continue;
        }
        if (count($headers) !== count($fields)) {
            $message = sprintf(
                'Header length does not match fields; Headers:%s Fields:%s',
                VarPrinter::toString($headers),
                VarPrinter::toString($fields)
            );
            throw new RuntimeException($message);
        }
        yield array_combine($headers, $fields);
    }

    fclose($fp);
}

/**
 * Reads a CSV file using existing header line as array keys
 *
 * @throws DomainException When arguments are not valid
 * @throws RuntimeException When the file cannot be read
 */
function read_csv_headers(string $path): iterable
{
    $fp = @fopen($path, 'r');

    if ($fp === false) {
        $message = sprintf('Unable to open file for reading: %s', $path);
        throw new RuntimeException($message);
    }

    $headers = fgetcsv($fp);

    fclose($fp);

    return read_csv($path, 1, $headers);
}

/**
 * Creates ResultSet instance
 */
function result_set(
    int $page,
    int $perPage,
    int $totalRecords,
    ArrayList $records
): ResultSet {
    return new ResultSet($page, $perPage, $totalRecords, $records);
}

/**
 * Creates StringObject instance
 */
function string(string $value): StringObject
{
    return StringObject::create($value);
}

/**
 * Creates a Time instance from time values
 *
 * @throws DomainException When the time is not valid
 */
function time_create(
    int $hour,
    int $minute,
    int $second,
    int $microsecond = 0
): Time {
    return Time::create($hour, $minute, $second, $microsecond);
}

/**
 * Creates a Time instance for the current time
 */
function time_now(?string $timezone = null): Time
{
    return Time::now($timezone);
}

/**
 * Creates a Time instance from a native DateTime
 */
function time_from_native(DateTimeInterface $dateTime): Time
{
    return Time::fromNative($dateTime);
}

/**
 * Creates a Time instance from a string representation
 *
 * @throws DomainException When the value is invalid
 */
function time_from_string(string $value): Time
{
    return Time::fromString($value);
}

/**
 * Creates instance from a timestamp and timezone
 */
function time_from_timestamp(int $timestamp, ?string $timezone = null): Time
{
    return Time::fromTimestamp($timestamp, $timezone);
}

/**
 * Constructs Timezone
 *
 * @throws DomainException When the value is not a valid timezone
 */
function timezone(mixed $value): Timezone
{
    return Timezone::create($value);
}

/**
 * Creates type instance
 */
function type(object|string $object): Type
{
    return Type::create($object);
}

/**
 * Creates a Uri instance from a URI string
 *
 * @throws DomainException When the URI is not valid
 */
function uri(string $uri): Uri
{
    return Uri::parse($uri);
}

/**
 * Creates a Uri instance from a base URI and relative reference
 *
 * @link http://tools.ietf.org/html/rfc3986#section-5.2
 *
 * @throws DomainException When the base or reference are invalid
 */
function uri_resolve(
    Uri|string $base,
    string $reference,
    bool $strict = true
): Uri {
    return Uri::resolve($base, $reference, $strict);
}

/**
 * Creates a Url instance from a URL string
 *
 * @throws DomainException When the URL is not valid
 */
function url(string $url): Url
{
    return Url::parse($url);
}

/**
 * Creates a Url instance from a base URI and relative reference
 *
 * @link http://tools.ietf.org/html/rfc3986#section-5.2
 *
 * @throws DomainException When the base or reference are invalid
 */
function url_resolve(
    Uri|string $base,
    string $reference,
    bool $strict = true
): Url {
    return Url::resolve($base, $reference, $strict);
}

/**
 * Creates a Uuid instance from a UUID string
 *
 * @throws DomainException When the string is not a valid UUID
 */
function uuid(string $uuid): Uuid
{
    return Uuid::parse($uuid);
}

/**
 * Creates a sequential pseudo-random Uuid instance
 *
 * This variation is not covered by RFC 4122, but it should provide
 * performance increases when using UUIDs as primary keys. The timestamp
 * should cover most significant bits or least significant bits depending
 * on how the database orders GUID values.
 */
function uuid_comb(bool $msb = true): Uuid
{
    return Uuid::comb($msb);
}

/**
 * Creates a Uuid instance from a byte string
 *
 * @throws DomainException When the bytes string is not valid
 */
function uuid_from_bytes(string $bytes): Uuid
{
    return Uuid::fromBytes($bytes);
}

/**
 * Creates a Uuid instance from a hexadecimal string
 *
 * @throws DomainException When the hex string is not valid
 */
function uuid_from_hex(string $hex): Uuid
{
    return Uuid::fromHex($hex);
}

/**
 * Creates a named instance
 *
 * @throws DomainException When the namespace is not a valid UUID
 */
function uuid_named(Uuid|string $namespace, string $name): Uuid
{
    return Uuid::named($namespace, $name);
}

/**
 * Creates a pseudo-random Uuid instance
 */
function uuid_random(): Uuid
{
    return Uuid::random();
}

/**
 * Creates a time-based instance
 *
 * If node, clock sequence, or timestamp values are not passed in, they
 * will be generated.
 *
 * Generating the node and clock sequence are not recommended for this UUID
 * version. The current timestamp can be retrieved in the correct format
 * from the `Uuid::timestamp()` static method.
 *
 * @link http://tools.ietf.org/html/rfc4122#section-4.1.5
 * @link http://tools.ietf.org/html/rfc4122#section-4.1.6
 *
 * @throws DomainException When clockSeq is not 14-bit unsigned
 * @throws DomainException When the node is not 6 hex octets
 * @throws DomainException When timestamp is not 8 hex octets
 */
function uuid_time(
    ?string $node = null,
    ?int $clockSeq = null,
    ?string $timestamp = null
): Uuid {
    return Uuid::time($node, $clockSeq, $timestamp);
}

/**
 * Validates that array keys exist on given data
 *
 * @throws DomainException When data is missing any keys
 */
function validate_array_keys(array $keys, array $data): void
{
    foreach ($keys as $key) {
        if (!array_key_exists($key, $data)) {
            $message = sprintf(
                'Invalid data format: %s',
                VarPrinter::toString($data)
            );
            throw new DomainException($message);
        }
    }
}

/**
 * Writes a CSV file
 *
 * Returns the number of rows written
 *
 * @throws DomainException When arguments are not valid
 * @throws RuntimeException When the file cannot be written
 */
function write_csv(
    string $path,
    iterable $data,
    Arrayable|array|null $headers = null
): int {
    $fp = @fopen($path, 'w');

    if ($fp === false) {
        $message = sprintf('Unable to open file for writing: %s', $path);
        throw new RuntimeException($message);
    }

    $count = 0;
    foreach ($data as $fields) {
        if ($fields instanceof Arrayable) {
            $fields = $fields->toArray();
        }
        if (!is_array($fields)) {
            $message = sprintf(
                'write_csv expects $data to be a list of arrays or %s instances',
                Arrayable::class
            );
            throw new DomainException($message);
        }

        // write headers and validate length of headers and fields
        if ($count === 0 && $headers !== null) {
            if ($headers instanceof Arrayable) {
                $headers = $headers->toArray();
            }
            if (!is_array($headers)) {
                $message = sprintf(
                    'write_csv expects $headers to be an array or %s',
                    Arrayable::class
                );
                throw new DomainException($message);
            }
            if (count($headers) !== count($fields)) {
                $message = sprintf(
                    'Header length does not match fields; Headers:%s Fields:%s',
                    VarPrinter::toString($headers),
                    VarPrinter::toString($fields)
                );
                throw new RuntimeException($message);
            }
            fputcsv($fp, $headers);
        }

        fputcsv($fp, $fields);
        $count++;
    }

    fclose($fp);

    return $count;
}
