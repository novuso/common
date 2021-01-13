<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Service\Exception;

use Psr\Container\NotFoundExceptionInterface;
use Throwable;

/**
 * Class ServiceNotFoundException
 */
class ServiceNotFoundException extends ServiceContainerException implements NotFoundExceptionInterface
{
    /**
     * Creates exception for a given service name
     */
    public static function fromName(
        string $name,
        ?Throwable $previous = null
    ): static {
        $message = sprintf('Undefined service: %s', $name);

        return new static($message, $name, $previous);
    }
}
