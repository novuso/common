<?php declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Data;

use Novuso\System\Collection\Contract\Collection;
use Novuso\System\Collection\HashSet;
use Novuso\System\Collection\HashTable;
use Novuso\System\Exception\KeyException;
use Novuso\System\Type\Arrayable;

/**
 * Class ErrorData
 */
class ErrorData implements Arrayable, Collection
{
    /**
     * Application data
     *
     * @var HashTable
     */
    protected $data;

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
     *
     * @param string $name The name
     *
     * @return array
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
     *
     * @param string $name The name
     *
     * @return bool
     */
    public function has(string $name): bool
    {
        return $this->data->has($name);
    }

    /**
     * Retrieves a list of names
     *
     * @return array
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
     * {@inheritdoc}
     */
    public function isEmpty(): bool
    {
        return $this->data->isEmpty();
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return $this->data->count();
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return $this->data->getIterator();
    }

    /**
     * {@inheritdoc}
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
