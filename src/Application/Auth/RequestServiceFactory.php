<?php declare(strict_types=1);

namespace Novuso\Common\Application\Auth;

use Novuso\System\Exception\DomainException;

/**
 * Interface RequestServiceFactory
 */
interface RequestServiceFactory
{
    /**
     * Creates a request service for basic authentication
     *
     * @param string $username The username
     * @param string $password The password
     *
     * @return RequestService
     *
     * @throws DomainException When the username is invalid
     */
    public function createBasicRequestService(string $username, string $password): RequestService;

    /**
     * Creates a request service for HMAC authentication
     *
     * @param string $publicKey  The public key
     * @param string $privateKey The private key
     *
     * @return RequestService
     */
    public function createHmacRequestService(string $publicKey, string $privateKey): RequestService;
}
