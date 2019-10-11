<?php declare(strict_types=1);

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
     * Service name
     *
     * @var string|null
     */
    protected $service;

    /**
     * Constructs ServiceContainerException
     *
     * @param string         $message  The message
     * @param string|null    $service  The service name
     * @param Throwable|null $previous The previous exception
     */
    public function __construct(string $message, ?string $service = null, ?Throwable $previous = null)
    {
        $this->service = $service;
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
