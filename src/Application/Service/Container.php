<?php declare(strict_types=1);

namespace Novuso\Common\Application\Service;

use Novuso\Common\Application\Service\Exception\ServiceContainerException;

/**
 * Container is the interface for a service container
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface Container
{
    /**
     * Retrieves a service by name
     *
     * @param string $name The service name
     *
     * @return mixed
     *
     * @throws ServiceContainerException When unable to load the service
     */
    public function get(string $name);

    /**
     * Checks if a service is defined
     *
     * @param string $name The service name
     *
     * @return bool
     */
    public function has(string $name): bool;
}
