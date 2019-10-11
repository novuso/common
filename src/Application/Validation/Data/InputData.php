<?php declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Data;

use Novuso\System\Collection\Contract\Collection;
use Novuso\System\Collection\HashTable;
use Novuso\System\Exception\KeyException;
use Novuso\System\Type\Arrayable;

/**
 * Class InputData
 */
class InputData implements Arrayable, Collection
{
    /**
     * Input data
     *
     * @var HashTable
     */
    protected $data;

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
     * @param string $name The name
     *
     * @return mixed
     *
     * @throws KeyException When the name is not defined
     */
    public function get(string $name)
    {
        return $this->data->get($name);
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
