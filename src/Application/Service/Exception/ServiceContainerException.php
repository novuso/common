<?php declare(strict_types=1);

namespace Novuso\Common\Application\Service\Exception;

use Exception;
use Novuso\System\Exception\RuntimeException;

/**
 * ServiceContainerException is thrown for service container errors
 *
 * @copyright Copyright (c) 2016, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class ServiceContainerException extends RuntimeException
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
     * @param Exception|null $previous The previous exception
     */
    public function __construct(string $message, string $service = null, Exception $previous = null)
    {
        $this->service = $service;
        parent::__construct($message, 0, $previous);
    }

    /**
     * Returns the service name
     *
     * @return string|null
     */
    public function getService()
    {
        return $this->service;
    }
}
