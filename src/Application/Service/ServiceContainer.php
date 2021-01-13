<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Service;

use ArrayAccess;
use Novuso\Common\Application\Service\Exception\ServiceNotFoundException;
use Psr\Container\ContainerInterface;

/**
 * Class ServiceContainer
 */
final class ServiceContainer implements ArrayAccess, ContainerInterface
{
    protected array $services = [];
    protected array $parameters = [];

    /**
     * Defines an object factory
     */
    public function factory(string $name, callable $callback): void
    {
        $this->services[$name] = $callback;
    }

    /**
     * Defines a shared service factory
     */
    public function set(string $name, callable $callback): void
    {
        $this->services[$name] = function ($c) use ($callback) {
            static $object;

            if ($object === null) {
                $object = $callback($c);
            }

            return $object;
        };
    }

    /**
     * Retrieves a service by name
     *
     * @param string $name The service name
     *
     * @throws ServiceNotFoundException When the service is not found
     */
    public function get($name): mixed
    {
        if (!isset($this->services[$name])) {
            throw ServiceNotFoundException::fromName($name);
        }

        return $this->services[$name]($this);
    }

    /**
     * Checks if a service is defined
     *
     * @param string $name The service name
     */
    public function has($name): bool
    {
        return isset($this->services[$name]);
    }

    /**
     * Removes a service
     */
    public function remove(string $name): void
    {
        unset($this->services[$name]);
    }

    /**
     * Sets a config parameter
     */
    public function setParameter(string $name, mixed $value): void
    {
        $this->parameters[$name] = $value;
    }

    /**
     * Retrieves a config parameter
     */
    public function getParameter(string $name, mixed $default = null): mixed
    {
        $isSet = isset($this->parameters[$name])
            || array_key_exists($name, $this->parameters);

        if (!($isSet)) {
            return $default;
        }

        return $this->parameters[$name];
    }

    /**
     * Checks if a parameter exists
     */
    public function hasParameter(string $name): bool
    {
        return isset($this->parameters[$name])
            || array_key_exists($name, $this->parameters);
    }

    /**
     * Removes a parameter
     */
    public function removeParameter(string $name): void
    {
        unset($this->parameters[$name]);
    }

    /**
     * Sets a config parameter
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->setParameter($offset, $value);
    }

    /**
     * Retrieves a config parameter
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->getParameter($offset);
    }

    /**
     * Checks if a parameter exists
     */
    public function offsetExists(mixed $offset): bool
    {
        return $this->hasParameter($offset);
    }

    /**
     * Removes a parameter
     */
    public function offsetUnset(mixed $offset): void
    {
        $this->removeParameter($offset);
    }
}
