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
     * @throws ConfigLoaderException When an error occurs
     */
    public function load(mixed $resource, ?string $type = null): ConfigContainer;

    /**
     * Checks if a resource is supported
     */
    public function supports(mixed $resource, ?string $type = null): bool;
}
