<?php declare(strict_types=1);

namespace Novuso\Common\Application\Service\Exception;

use Psr\Container\NotFoundExceptionInterface;
use Throwable;

/**
 * ServiceNotFoundException is thrown when a service is not defined
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class ServiceNotFoundException extends ServiceContainerException implements NotFoundExceptionInterface
{
    /**
     * Creates exception for a given service name
     *
     * @param string         $name     The service name
     * @param Throwable|null $previous The previous exception
     *
     * @return ServiceNotFoundException
     */
    public static function fromName(string $name, ?Throwable $previous = null): ServiceNotFoundException
    {
        $message = sprintf('Undefined service: %s', $name);

        return new static($message, $name, $previous);
    }
}
