<?php declare(strict_types=1);

namespace Novuso\Common\Application\Auth;

use Psr\Http\Message\ServerRequestInterface;
use Throwable;

/**
 * Interface Authenticator
 */
interface Authenticator
{
    /**
     * Validates a server request authentication
     *
     * @param ServerRequestInterface $request The request
     *
     * @return bool
     *
     * @throws Throwable When an error occurs
     */
    public function validate(ServerRequestInterface $request): bool;

    /**
     * Retrieves the error code
     *
     * @return int|null
     */
    public function getErrorCode(): ?int;

    /**
     * Retrieves the error message
     *
     * @return string|null
     */
    public function getErrorMessage(): ?string;
}
