<?php declare(strict_types=1);

namespace Novuso\Common\Application\Config;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Novuso\Common\Application\Config\Exception\FrozenContainerException;
use Novuso\System\Type\Arrayable;
use Traversable;

/**
 * ConfigContainer is a configuration container
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class ConfigContainer implements Arrayable, ArrayAccess, Countable, IteratorAggregate
{
    /**
     * Data
     *
     * @var array
     */
    protected $data = [];

    /**
     * Frozen status
     *
     * @var bool
     */
    protected $frozen = false;

    /**
     * Constructs ConfigContainer
     *
     * @param array $data The initial configuration
     */
    public function __construct(array $data = [])
    {
        foreach ($data as $name => $value) {
            $this->set($name, $value);
        }
    }

    /**
     * Handles deep cloning
     *
     * @return void
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

    /**
     * Checks if closed to modification
     *
     * @return bool
     */
    public function isFrozen(): bool
    {
        return $this->frozen;
    }

    /**
     * Checks if empty
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->data);
    }

    /**
     * Retrieves the count
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->data);
    }

    /**
     * Sets a value
     *
     * @param string|int|null $name  The config name
     * @param mixed           $value The config value
     *
     * @return void
     *
     * @throws FrozenContainerException When container is frozen
     */
    public function set($name, $value): void
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
     *
     * @param string|int $name    The config name
     * @param mixed      $default The default value
     *
     * @return mixed
     */
    public function get($name, $default = null)
    {
        if (!array_key_exists($name, $this->data)) {
            return $default;
        }

        return $this->data[$name];
    }

    /**
     * Checks if a value exists
     *
     * @param string|int $name The config name
     *
     * @return bool
     */
    public function has($name): bool
    {
        return array_key_exists($name, $this->data);
    }

    /**
     * Removes a value
     *
     * @param string|int $name The config name
     *
     * @return void
     *
     * @throws FrozenContainerException When container is frozen
     */
    public function remove($name): void
    {
        if ($this->isFrozen()) {
            throw new FrozenContainerException('Container is frozen');
        }

        unset($this->data[$name]);
    }

    /**
     * Sets a value
     *
     * @param string|int|null $name  The config name
     * @param mixed           $value The config value
     *
     * @return void
     *
     * @throws FrozenContainerException When container is frozen
     */
    public function offsetSet($name, $value): void
    {
        $this->set($name, $value);
    }

    /**
     * Retrieves a value
     *
     * @param string|int $name The config name
     *
     * @return mixed
     */
    public function offsetGet($name)
    {
        return $this->get($name);
    }

    /**
     * Checks if a value exists
     *
     * @param string|int $name The config name
     *
     * @return bool
     */
    public function offsetExists($name): bool
    {
        return $this->has($name);
    }

    /**
     * Removes a value
     *
     * @param string|int $name The config name
     *
     * @return void
     *
     * @throws FrozenContainerException When container is frozen
     */
    public function offsetUnset($name): void
    {
        $this->remove($name);
    }

    /**
     * Retrieves a list of keys
     *
     * @return array
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
     * @param ConfigContainer $other The container to merge
     *
     * @return ConfigContainer
     *
     * @throws FrozenContainerException When either container is frozen
     */
    public function merge(ConfigContainer $other): ConfigContainer
    {
        if ($this->isFrozen()) {
            throw new FrozenContainerException('Container is frozen');
        }
        if ($other->isFrozen()) {
            throw new FrozenContainerException('Container is frozen');
        }

        foreach ($other as $name => $value) {
            if (!array_key_exists($name, $this->data)) {
                $this->data[$name] = $value;
            } else {
                if (is_int($name)) {
                    $this->data[] = $value;
                } elseif ($value instanceof self && $this->data[$name] instanceof self) {
                    $this->data[$name]->merge($value);
                } else {
                    $this->data[$name] = $value;
                }
            }
        }

        return $this;
    }

    /**
     * {@inheritdoc}
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
     *
     * @return Traversable
     */
    public function getIterator(): Traversable
    {
        $data = $this->data;

        return new ArrayIterator($data);
    }

    /**
     * Freezes the container from further modification
     *
     * @return void
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
}
