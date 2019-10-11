<?php declare(strict_types=1);

namespace Novuso\Common\Application\Security;

/**
 * Interface PasswordValidator
 */
interface PasswordValidator
{
    /**
     * Validates a password against a given hash
     *
     * @param string $password The plain-text password to validate
     * @param string $hash     The hash with which to verify the plain-text password
     *
     * @return bool
     */
    public function validate(string $password, string $hash): bool;

    /**
     * Checks to see if the given hash is compatible with the validator
     *
     * @param string $hash The password hash
     *
     * @return bool
     */
    public function needsRehash(string $hash): bool;
}
