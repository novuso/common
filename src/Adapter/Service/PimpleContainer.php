<?php declare(strict_types=1);

namespace Novuso\Common\Adapter\Service;

use Novuso\Common\Application\Service\Exception\ServiceContainerException;
use Novuso\Common\Application\Service\Exception\ServiceNotFoundException;
use Pimple\Container;
use Psr\Container\ContainerInterface;
use Throwable;

/**
 * PimpleContainer is a PSR-11 adapter for a pimple container
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class PimpleContainer implements ContainerInterface
{
    /**
     * Pimple container
     *
     * @var Container
     */
    protected $container;

    /**
     * Constructs PimpleContainer
     *
     * @param Container $container The service container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Retrieves a service by identifier
     *
     * @param string $id The service ID
     *
     * @return mixed
     *
     * @throws ServiceNotFoundException When the service is not found
     * @throws ServiceContainerException When an error occurs
     */
    public function get($id)
    {
        if (!isset($this->container[$id])) {
            throw ServiceNotFoundException::fromName($id);
        }

        try {
            return $this->container[$id];
        } catch (Throwable $e) {
            throw new ServiceContainerException($e->getMessage(), $id, $e);
        }
    }

    /**
     * Checks if a service is defined
     *
     * @param string $id The service ID
     *
     * @return bool
     */
    public function has($id): bool
    {
        return isset($this->container[$id]);
    }
}
