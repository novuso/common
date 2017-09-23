<?php declare(strict_types=1);

namespace Novuso\Common\Application\Config;

use Novuso\Common\Application\Config\Exception\ConfigLoaderException;

/**
 * ConfigLoader is the interface for a configuration loader
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface ConfigLoader
{
    /**
     * Loads a resource
     *
     * @param mixed       $resource The config resource
     * @param null|string $type     The resource type
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
     * @param null|string $type     The resource type
     *
     * @return bool
     */
    public function supports($resource, ?string $type = null): bool;
}
