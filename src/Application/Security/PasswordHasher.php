<?php declare(strict_types=1);

namespace Novuso\Common\Application\Security;

use Novuso\Common\Application\Security\Exception\PasswordException;

/**
 * Interface PasswordHasher
 */
interface PasswordHasher
{
    /**
     * Hashes a password
     *
     * @param string $password The plain-text password to hash
     *
     * @return string
     *
     * @throws PasswordException When the password cannot be hashed
     */
    public function hash(string $password): string;
}
