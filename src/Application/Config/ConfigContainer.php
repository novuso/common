<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Config;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Novuso\Common\Application\Config\Exception\FrozenContainerException;
use Novuso\System\Type\Arrayable;
use Traversable;

/**
 * Class ConfigContainer
 */
final class ConfigContainer implements Arrayable, ArrayAccess, Countable, IteratorAggregate
{
    protected array $data = [];
    protected bool $frozen = false;

    /**
     * Constructs ConfigContainer
     */
    public function __construct(array $data = [])
    {
        foreach ($data as $name => $value) {
            $this->set($name, $value);
        }
    }

    /**
     * Checks if closed to modification
     */
    public function isFrozen(): bool
    {
        return $this->frozen;
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
     * Sets a value
     *
     * @throws FrozenContainerException When container is frozen
     */
    public function set(string|int|null $name, mixed $value): void
    {
        if ($this->isFrozen()) {
            throw new FrozenContainerException('Container is frozen');
        }

        if (is_array($value)) {
            $value = new static($value);
        }

        if ($name === null) {
            $this->data[] = $value;
        } else {
            $this->data[$name] = $value;
        }
    }

    /**
     * Retrieves a value
     */
    public function get(string|int $name, mixed $default = null): mixed
    {
        if (!array_key_exists($name, $this->data)) {
            return $default;
        }

        return $this->data[$name];
    }

    /**
     * Checks if a value exists
     */
    public function has(string|int $name): bool
    {
        return array_key_exists($name, $this->data);
    }

    /**
     * Removes a value
     *
     * @throws FrozenContainerException When container is frozen
     */
    public function remove(string|int $name): void
    {
        if ($this->isFrozen()) {
            throw new FrozenContainerException('Container is frozen');
        }

        unset($this->data[$name]);
    }

    /**
     * Sets a value
     *
     * @throws FrozenContainerException When container is frozen
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->set($offset, $value);
    }

    /**
     * Retrieves a value
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->get($offset);
    }

    /**
     * Checks if a value exists
     */
    public function offsetExists(mixed $offset): bool
    {
        return $this->has($offset);
    }

    /**
     * Removes a value
     *
     * @throws FrozenContainerException When container is frozen
     */
    public function offsetUnset(mixed $offset): void
    {
        $this->remove($offset);
    }

    /**
     * Retrieves a list of keys
     */
    public function keys(): array
    {
        return array_keys($this->data);
    }

    /**
     * Merges another config container
     *
     * Behavior for duplicate keys:
     * - Nested containers are merged recursively
     * - Values with integer keys are appended
     * - Values with string keys will overwrite
     *
     * @throws FrozenContainerException When either container is frozen
     */
    public function merge(ConfigContainer $other): static
    {
        if ($this->isFrozen()) {
            throw new FrozenContainerException('Container is frozen');
        }
        if ($other->isFrozen()) {
            throw new FrozenContainerException('Container is frozen');
        }

        foreach ($other as $name => $value) {
            // key does not exist
            if (!array_key_exists($name, $this->data)) {
                $this->data[$name] = $value;
                continue;
            }
            // key exists
            if (is_int($name)) {
                $this->data[] = $value;
            } elseif (
                $value instanceof self
                && $this->data[$name] instanceof self
            ) {
                $this->data[$name]->merge($value);
            } else {
                $this->data[$name] = $value;
            }
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $data = [];

        foreach ($this->data as $name => $value) {
            if ($value instanceof self) {
                $data[$name] = $value->toArray();
            } else {
                $data[$name] = $value;
            }
        }

        return $data;
    }

    /**
     * Retrieves an iterator
     */
    public function getIterator(): Traversable
    {
        $data = $this->data;

        return new ArrayIterator($data);
    }

    /**
     * Freezes the container from further modification
     */
    public function freeze(): void
    {
        $this->frozen = true;
        foreach ($this->data as $value) {
            if ($value instanceof self) {
                $value->freeze();
            }
        }
    }

    /**
     * Handles deep cloning
     */
    public function __clone()
    {
        $data = [];

        foreach ($this->data as $name => $value) {
            if ($value instanceof self) {
                $data[$name] = clone $value;
            } else {
                $data[$name] = $value;
            }
        }

        $this->data = $data;
    }
}
