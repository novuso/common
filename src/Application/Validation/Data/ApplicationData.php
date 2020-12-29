<?php declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Data;

use Novuso\System\Collection\Contract\Collection;
use Novuso\System\Collection\HashTable;
use Novuso\System\Exception\KeyException;
use Novuso\System\Type\Arrayable;

/**
 * Class ApplicationData
 */
class ApplicationData implements Arrayable, Collection
{
    /**
     * Application data
     *
     * @var HashTable
     */
    protected $data;

    /**
     * Constructs ApplicationData
     *
     * @param array $data An associated array keyed by field name
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
     * @param string $name    The name
     * @param mixed  $default Default value
     *
     * @return mixed
     */
    public function get(string $name, $default = null)
    {
        try {
            return $this->data->get($name);
        } catch (KeyException $e) {
            return $default;
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
     * @return array<string>
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
        $data = [];

        foreach ($this->data as $name => $value) {
            $data[$name] = $value;
        }

        return $data;
    }
}
