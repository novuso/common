<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Data;

use Novuso\System\Collection\Contract\Collection;
use Novuso\System\Collection\HashTable;
use Novuso\System\Exception\KeyException;
use Novuso\System\Type\Arrayable;
use Traversable;

/**
 * Class ApplicationData
 */
class ApplicationData implements Arrayable, Collection
{
    protected HashTable $data;

    /**
     * Constructs ApplicationData
     */
    public function __construct(array $data)
    {
        $this->data = HashTable::of('string');
        foreach ($data as $name => $value) {
            $this->data->set($name, $value);
        }
    }

    /**
     * Retrieves a value by field name
     */
    public function get(string $name, mixed $default = null): mixed
    {
        try {
            return $this->data->get($name);
        } catch (KeyException $e) {
            return $default;
        }
    }

    /**
     * Checks if a name is defined
     */
    public function has(string $name): bool
    {
        return $this->data->has($name);
    }

    /**
     * Retrieves a list of names
     */
    public function names(): array
    {
        $names = [];

        foreach ($this->data->keys() as $name) {
            $names[] = $name;
        }

        return $names;
    }

    /**
     * @inheritDoc
     */
    public function isEmpty(): bool
    {
        return $this->data->isEmpty();
    }

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        return $this->data->count();
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): Traversable
    {
        return $this->data->getIterator();
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $data = [];

        foreach ($this->data as $name => $value) {
            $data[$name] = $value;
        }

        return $data;
    }
}
