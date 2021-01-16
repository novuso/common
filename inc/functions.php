<?php

declare(strict_types=1);

namespace Novuso\Common;

use Novuso\Common\Domain\Type\JsonObject;
use Novuso\Common\Domain\Type\MbStringObject;
use Novuso\Common\Domain\Type\StringObject;
use Novuso\Common\Domain\Value\Communication\EmailAddress;
use Novuso\System\Collection\ArrayList;
use Novuso\System\Collection\HashSet;
use Novuso\System\Exception\DomainException;
use Novuso\System\Exception\RuntimeException;
use Novuso\System\Type\Arrayable;
use Novuso\System\Type\Type;
use Novuso\System\Utility\Environment;
use Novuso\System\Utility\VarPrinter;

if (!function_exists('array_list')) {
    /**
     * Creates an ArrayList instance
     *
     * If a type is not provided, the item type is dynamic.
     *
     * The type can be any fully-qualified class or interface name,
     * or one of the following type strings:
     * [array, object, bool, int, float, string, callable]
     */
    function array_list(array $items = [], ?string $itemType = null): ArrayList
    {
        return ArrayList::of($itemType)->replace($items);
    }
}

if (!function_exists('base64url_encode')) {
    /**
     * Encodes data with URL safe base64 encoding
     *
     * @link https://brockallen.com/2014/10/17/base64url-encoding/ base64url
     */
    function base64url_encode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}

if (!function_exists('base64url_decode')) {
    /**
     * Decodes data encoded with URL safe base64 encoding
     *
     * @link https://brockallen.com/2014/10/17/base64url-encoding/ base64url
     */
    function base64url_decode(string $data): string
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }
}

if (!function_exists('email_address')) {
    /**
     * Creates an EmailAddress instance
     *
     * @throws DomainException When the email address is invalid
     */
    function email_address(string $emailAddress): EmailAddress
    {
        return EmailAddress::fromString($emailAddress);
    }
}

if (!function_exists('env')) {
    /**
     * Retrieves the value of an environment variable
     */
    function env(
        string $key,
        string|bool|null $default = null
    ): string|bool|null {
        return Environment::get($key, $default);
    }
}

if (!function_exists('hash_set')) {
    /**
     * Creates an HashSet instance
     *
     * If a type is not provided, the item type is dynamic.
     *
     * The type can be any fully-qualified class or interface name,
     * or one of the following type strings:
     * [array, object, bool, int, float, string, callable]
     */
    function hash_set(array $items = [], ?string $itemType = null): HashSet
    {
        $set = HashSet::of($itemType);

        foreach ($items as $item) {
            $set->add($item);
        }

        return $set;
    }
}

if (!function_exists('json_data')) {
    /**
     * Creates JsonObject instance from data
     *
     * @throws DomainException When data is not JSON encodable
     */
    function json_data(mixed $data, ?int $encodingOptions = null): JsonObject
    {
        return JsonObject::fromData($data, $encodingOptions);
    }
}

if (!function_exists('json_string')) {
    /**
     * Creates JsonObject instance from JSON string
     *
     * @throws DomainException When JSON string is invalid
     */
    function json_string(string $json): JsonObject
    {
        return JsonObject::fromString($json);
    }
}

if (!function_exists('mb_string')) {
    /**
     * Creates MbStringObject instance
     */
    function mb_string(string $value): MbStringObject
    {
        return MbStringObject::create($value);
    }
}

if (!function_exists('read_csv')) {
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
}

if (!function_exists('read_csv_headers')) {
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
}

if (!function_exists('string')) {
    /**
     * Creates StringObject instance
     */
    function string(string $value): StringObject
    {
        return StringObject::create($value);
    }
}

if (!function_exists('type')) {
    /**
     * Creates type instance
     */
    function type(object|string $object): Type
    {
        return Type::create($object);
    }
}

if (!function_exists('validate_array_keys')) {
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
}

if (!function_exists('write_csv')) {
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
}
