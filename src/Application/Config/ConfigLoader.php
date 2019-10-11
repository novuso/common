<?php declare(strict_types=1);

namespace Novuso\Common\Application\Config;

use Novuso\Common\Application\Config\Exception\ConfigLoaderException;

/**
 * Interface ConfigLoader
 */
interface ConfigLoader
{
    /**
     * Loads a resource
     *
     * @param mixed       $resource The config resource
     * @param string|null $type     The resource type
     *
     * @return ConfigContainer
     *
     * @throws ConfigLoaderException When an error occurs
     */
    public function load($resource, ?string $type = null): ConfigContainer;

    /**
     * Checks if a resource is supported
     *
     * @param mixed       $resource The config resource
     * @param string|null $type     The resource type
     *
     * @return bool
     */
    public function supports($resource, ?string $type = null): bool;
}
