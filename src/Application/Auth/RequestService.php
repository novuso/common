<?php declare(strict_types=1);

namespace Novuso\Common\Application\Auth;

use Psr\Http\Message\RequestInterface;
use Throwable;

/**
 * Interface RequestService
 */
interface RequestService
{
    /**
     * Signs a request with authentication credentials
     *
     * @throws Throwable When an error occurs
     */
    public function signRequest(RequestInterface $request): RequestInterface;
}
