<?php declare(strict_types=1);

namespace Novuso\Common\Application\Container;

use ArrayAccess;
use Novuso\Common\Application\Container\Exception\ServiceNotFoundException;
use Psr\Container\ContainerInterface;

/**
 * Class ServiceContainer
 */
class ServiceContainer implements ArrayAccess, ContainerInterface
{
    /**
     * Service factories
     *
     * @var array
     */
    protected $services = [];

    /**
     * Config parameters
     *
     * @var array
     */
    protected $parameters = [];

    /**
     * Defines an object factory
     *
     * @param string   $name     The service name
     * @param callable $callback The object factory callback
     *
     * @return void
     */
    public function factory(string $name, callable $callback): void
    {
        $this->services[$name] = $callback;
    }

    /**
     * Defines a shared service factory
     *
     * @param string   $name     The service name
     * @param callable $callback The object factory callback
     *
     * @return void
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
     * @return mixed
     *
     * @throws ServiceNotFoundException When the service is not found
     */
    public function get($name)
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
     *
     * @return bool
     */
    public function has($name): bool
    {
        return isset($this->services[$name]);
    }

    /**
     * Removes a service
     *
     * @param string $name The service name
     *
     * @return void
     */
    public function remove(string $name): void
    {
        unset($this->services[$name]);
    }

    /**
     * Sets a config parameter
     *
     * @param string $name  The parameter name
     * @param mixed  $value The parameter value
     *
     * @return void
     */
    public function setParameter(string $name, $value): void
    {
        $this->parameters[$name] = $value;
    }

    /**
     * Retrieves a config parameter
     *
     * @param string $name    The parameter name
     * @param mixed  $default A default value to return if not found
     *
     * @return mixed
     */
    public function getParameter(string $name, $default = null)
    {
        if (!(isset($this->parameters[$name]) || array_key_exists($name, $this->parameters))) {
            return $default;
        }

        return $this->parameters[$name];
    }

    /**
     * Checks if a parameter exists
     *
     * @param string $name The parameter name
     *
     * @return bool
     */
    public function hasParameter(string $name): bool
    {
        return isset($this->parameters[$name]) || array_key_exists($name, $this->parameters);
    }

    /**
     * Removes a parameter
     *
     * @param string $name The parameter name
     *
     * @return void
     */
    public function removeParameter(string $name): void
    {
        unset($this->parameters[$name]);
    }

    /**
     * Sets a config parameter
     *
     * @param string $name  The parameter name
     * @param mixed  $value The parameter value
     *
     * @return void
     */
    public function offsetSet($name, $value): void
    {
        $this->setParameter($name, $value);
    }

    /**
     * Retrieves a config parameter
     *
     * @param string $name The parameter name
     *
     * @return mixed
     */
    public function offsetGet($name)
    {
        return $this->getParameter($name);
    }

    /**
     * Checks if a parameter exists
     *
     * @param string $name The parameter name
     *
     * @return bool
     */
    public function offsetExists($name): bool
    {
        return $this->hasParameter($name);
    }

    /**
     * Removes a parameter
     *
     * @param string $name The parameter name
     *
     * @return void
     */
    public function offsetUnset($name): void
    {
        $this->removeParameter($name);
    }
}
