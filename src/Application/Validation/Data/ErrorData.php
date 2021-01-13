<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Data;

use Novuso\System\Collection\Contract\Collection;
use Novuso\System\Collection\HashSet;
use Novuso\System\Collection\HashTable;
use Novuso\System\Exception\KeyException;
use Novuso\System\Type\Arrayable;
use Traversable;

/**
 * Class ErrorData
 */
class ErrorData implements Arrayable, Collection
{
    protected HashTable $data;

    /**
     * Constructs ErrorData
     *
     * @param array $data An associated array keyed by field name
     *                    Values must be arrays of error messages
     */
    public function __construct(array $data)
    {
        $this->data = HashTable::of('string', HashSet::class);
        foreach ($data as $name => $messages) {
            $set = HashSet::of('string');
            foreach ($messages as $message) {
                $set->add($message);
            }
            $this->data->set($name, $set);
        }
    }

    /**
     * Retrieves a list of errors by field name
     */
    public function get(string $name): array
    {
        $errors = [];

        try {
            /** @var HashSet $set */
            $set = $this->data->get($name);

            foreach ($set as $message) {
                $errors[] = $message;
            }

            return $errors;
        } catch (KeyException $e) {
            return $errors;
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
        $errors = [];

        /** @var string $name @var HashSet $messages */
        foreach ($this->data as $name => $messages) {
            $errors[$name] = [];
            foreach ($messages as $message) {
                $errors[$name][] = $message;
            }
        }

        return $errors;
    }
}
