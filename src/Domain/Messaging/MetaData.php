<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Messaging;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;
use Novuso\System\Collection\ArrayCollection;
use Novuso\System\Exception\DomainException;
use Novuso\System\Type\Arrayable;
use Novuso\System\Utility\Test;
use Novuso\System\Utility\VarPrinter;
use Traversable;

/**
 * MetaData contains informational data related to a message
 *
 * @copyright Copyright (c) 2016, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class MetaData implements Arrayable, ArrayAccess, Countable, IteratorAggregate, JsonSerializable
{
    /**
     * Meta data
     *
     * @var array
     */
    protected $data = [];

    /**
     * Constructs MetaData
     *
     * @param array $data The initial meta data
     *
     * @throws DomainException When data is not valid
     */
    public function __construct(array $data = [])
    {
        ArrayCollection::create($data)->each(function ($value, $key) {
            $this->set($key, $value);
        });
    }

    /**
     * Creates instance from array representation
     *
     * @param array $data The array representation
     *
     * @return MetaData
     *
     * @throws DomainException When data is not valid
     */
    public static function create(array $data = []): MetaData
    {
        return new static($data);
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
     * Sets a key/value pair
     *
     * The value may be a scalar, array, or null. An array may be nested as
     * long as it does not contain any more complex types than arrays, scalars,
     * or null.
     *
     * @param string $key   The key
     * @param mixed  $value The value
     *
     * @return void
     *
     * @throws DomainException When value is not valid
     */
    public function set(string $key, $value)
    {
        $this->guardValue($value);
        $this->data[$key] = $value;
    }

    /**
     * Retrieves a value by key
     *
     * @param string $key     The key
     * @param mixed  $default The default to return if undefined
     *
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        if (!(isset($this->data[$key]) || array_key_exists($key, $this->data))) {
            return $default;
        }

        return $this->data[$key];
    }

    /**
     * Checks if a key is defined
     *
     * @param string $key The key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return isset($this->data[$key]) || array_key_exists($key, $this->data);
    }

    /**
     * Removes a key/value pair
     *
     * @param string $key The key
     *
     * @return void
     */
    public function remove(string $key)
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
     * @param string $key   The key
     * @param mixed  $value The value
     *
     * @return void
     *
     * @throws DomainException When value is not valid
     */
    public function offsetSet($key, $value)
    {
        assert(
            Test::isString($key),
            sprintf('Invalid metadata key: (%s) %s', gettype($key), VarPrinter::toString($key))
        );

        $this->set($key, $value);
    }

    /**
     * Retrieves a value by key
     *
     * @param string $key The key
     *
     * @return mixed
     */
    public function offsetGet($key)
    {
        assert(
            Test::isString($key),
            sprintf('Invalid metadata key: (%s) %s', gettype($key), VarPrinter::toString($key))
        );

        return $this->get($key);
    }

    /**
     * Checks if a key is defined
     *
     * @param string $key The key
     *
     * @return bool
     */
    public function offsetExists($key): bool
    {
        assert(
            Test::isString($key),
            sprintf('Invalid metadata key: (%s) %s', gettype($key), VarPrinter::toString($key))
        );

        return $this->has($key);
    }

    /**
     * Removes a key/value pair
     *
     * @param string $key The key
     *
     * @return void
     */
    public function offsetUnset($key)
    {
        assert(
            Test::isString($key),
            sprintf('Invalid metadata key: (%s) %s', gettype($key), VarPrinter::toString($key))
        );

        $this->remove($key);
    }

    /**
     * Retrieves a list of keys
     *
     * @return string[]
     */
    public function keys(): array
    {
        return array_keys($this->data);
    }

    /**
     * Merges the given meta data
     *
     * @param MetaData $data The meta data
     *
     * @return void
     */
    public function merge(MetaData $data)
    {
        foreach ($data->toArray() as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        return $this->data;
    }

    /**
     * Retrieves a string representation
     *
     * @return string
     */
    public function toString(): string
    {
        return json_encode($this->data, JSON_UNESCAPED_SLASHES);
    }

    /**
     * Handles casting to a string
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * Retrieves a value for JSON encoding
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->data;
    }

    /**
     * Retrieves an iterator
     *
     * @return Traversable
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->data);
    }

    /**
     * Validates value type
     *
     * @param mixed $value The value
     *
     * @return void
     *
     * @throws DomainException When value is not valid
     */
    protected function guardValue($value)
    {
        if (!$this->isValid($value)) {
            $message = 'Value must be scalar or an array of scalars';
            throw new DomainException($message);
        }
    }

    /**
     * Checks if a value is valid
     *
     * @param mixed $value The value
     *
     * @return bool
     */
    protected function isValid($value): bool
    {
        $type = gettype($value);
        switch ($type) {
            case 'string':
            case 'integer':
            case 'double':
            case 'boolean':
            case 'NULL':
                return true;
                break;
            case 'array':
                return ArrayCollection::create($value)->every(function ($v) {
                    return $this->isValid($v);
                });
                break;
            default:
                break;
        }

        return false;
    }
}
