<?php declare(strict_types=1);

namespace Novuso\Common\Application\Service;

use ArrayAccess;
use Novuso\Common\Application\Service\Exception\ServiceNotFoundException;

/**
 * ServiceContainer is an application service container
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class ServiceContainer implements ArrayAccess, Container
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
    public function factory(string $name, callable $callback)
    {
        $this->services[$name] = $callback;
    }

    /**
     * Defines a shared service
     *
     * @param string   $name     The service name
     * @param callable $callback The service factory callback
     *
     * @return void
     */
    public function set(string $name, callable $callback)
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
     * {@inheritdoc}
     */
    public function get(string $name)
    {
        if (!isset($this->services[$name])) {
            throw ServiceNotFoundException::fromName($name);
        }

        return $this->services[$name]($this);
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $name): bool
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
    public function remove(string $name)
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
    public function setParameter(string $name, $value)
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
    public function removeParameter(string $name)
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
    public function offsetSet($name, $value)
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
    public function offsetUnset($name)
    {
        $this->removeParameter($name);
    }
}
