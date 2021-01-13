<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Service\Exception;

use Novuso\System\Exception\SystemException;
use Psr\Container\ContainerExceptionInterface;
use Throwable;

/**
 * Class ServiceContainerException
 */
class ServiceContainerException extends SystemException implements ContainerExceptionInterface
{
    /**
     * Constructs ServiceContainerException
     */
    public function __construct(
        string $message,
        protected ?string $service = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, 0, $previous);
    }

    /**
     * Returns the service name
     *
     * @return string|null
     */
    public function getService(): ?string
    {
        return $this->service;
    }
}
