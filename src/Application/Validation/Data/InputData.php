<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Data;

use Novuso\System\Collection\Contract\Collection;
use Novuso\System\Collection\HashTable;
use Novuso\System\Exception\KeyException;
use Novuso\System\Type\Arrayable;
use Traversable;

/**
 * Class InputData
 */
class InputData implements Arrayable, Collection
{
    protected HashTable $data;

    /**
     * Constructs InputData
     *
     * @param array $data An associative array keyed by field name
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
     *
     * @throws KeyException When the name is not defined
     */
    public function get(string $name): mixed
    {
        return $this->data->get($name);
    }

    /**
     * Checks if a name is defined
     */
    public function has(string $name): bool
    {
        return $this->data->has($name);
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
