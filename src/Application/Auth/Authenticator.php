<?php

declare(strict_types=1);

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
     * @throws Throwable When an error occurs
     */
    public function validate(ServerRequestInterface $request): bool;

    /**
     * Retrieves the error code
     */
    public function getErrorCode(): ?int;

    /**
     * Retrieves the error message
     */
    public function getErrorMessage(): ?string;
}
