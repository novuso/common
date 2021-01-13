<?php

declare(strict_types=1);

namespace Novuso\Common\Domain\Messaging;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;
use Novuso\System\Exception\DomainException;
use Novuso\System\Type\Arrayable;
use Novuso\System\Utility\Assert;
use Traversable;

/**
 * Class MetaData
 */
final class MetaData implements Arrayable, ArrayAccess, Countable, IteratorAggregate, JsonSerializable
{
    protected array $data = [];

    /**
     * Constructs MetaData
     *
     * @throws DomainException When data is not valid
     */
    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * Creates instance from array representation
     *
     * @throws DomainException When data is not valid
     */
    public static function create(array $data = []): static
    {
        return new static($data);
    }

    /**
     * Checks if empty
     */
    public function isEmpty(): bool
    {
        return empty($this->data);
    }

    /**
     * Retrieves the count
     */
    public function count(): int
    {
        return count($this->data);
    }

    /**
     * Sets a key/value pair
     *
     * The value may be a scalar, array, or null. An array may be nested as
     * long as it does not contain any more complex types than arrays, scalars,
     * or null.
     *
     * @throws DomainException When value is not valid
     */
    public function set(string $key, mixed $value): void
    {
        $this->guardValue($value);
        $this->data[$key] = $value;
    }

    /**
     * Retrieves a value by key
     */
    public function get(string $key, mixed $default = null): mixed
    {
        if (
            !(isset($this->data[$key]) || array_key_exists($key, $this->data))
        ) {
            return $default;
        }

        return $this->data[$key];
    }

    /**
     * Checks if a key is defined
     */
    public function has(string $key): bool
    {
        return isset($this->data[$key]) || array_key_exists($key, $this->data);
    }

    /**
     * Removes a key/value pair
     */
    public function remove(string $key): void
    {
        unset($this->data[$key]);
    }

    /**
     * Sets a key/value pair
     *
     * The value may be a scalar, array, or null. An array may be nested as
     * long as it does not contain any more complex types than arrays, scalars,
     * or null.
     *
     * @throws DomainException When value is not valid
     */
    public function offsetSet(mixed $key, mixed $value): void
    {
        Assert::isString($key);

        $this->set($key, $value);
    }

    /**
     * Retrieves a value by key
     */
    public function offsetGet(mixed $key): mixed
    {
        Assert::isString($key);

        return $this->get($key);
    }

    /**
     * Checks if a key is defined
     */
    public function offsetExists(mixed $key): bool
    {
        Assert::isString($key);

        return $this->has($key);
    }

    /**
     * Removes a key/value pair
     */
    public function offsetUnset(mixed $key): void
    {
        Assert::isString($key);

        $this->remove($key);
    }

    /**
     * Retrieves a list of keys
     */
    public function keys(): array
    {
        return array_keys($this->data);
    }

    /**
     * Merges the given meta data
     *
     * @throws DomainException When value is not valid
     */
    public function merge(MetaData $data): void
    {
        foreach ($data->toArray() as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return $this->data;
    }

    /**
     * Retrieves a string representation
     */
    public function toString(): string
    {
        return json_encode($this->data, JSON_UNESCAPED_SLASHES);
    }

    /**
     * Handles casting to a string
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * Retrieves a value for JSON encoding
     */
    public function jsonSerialize(): array
    {
        return $this->data;
    }

    /**
     * Retrieves an iterator
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->data);
    }

    /**
     * Validates value type
     *
     * @throws DomainException When value is not valid
     */
    protected function guardValue(mixed $value): void
    {
        if (!$this->isValid($value)) {
            $message = 'Value must be scalar or an array of scalars';
            throw new DomainException($message);
        }
    }

    /**
     * Checks if a value is valid
     */
    protected function isValid(mixed $value): bool
    {
        $type = gettype($value);
        switch ($type) {
            case 'string':
            case 'integer':
            case 'double':
            case 'boolean':
            case 'NULL':
                return true;
            case 'array':
                foreach ($value as $val) {
                    if (!$this->isValid($val)) {
                        return false;
                    }
                }

                return true;
            default:
                break;
        }

        return false;
    }
}
