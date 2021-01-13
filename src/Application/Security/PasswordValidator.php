<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Security;

/**
 * Interface PasswordValidator
 */
interface PasswordValidator
{
    /**
     * Validates a password against a given hash
     */
    public function validate(string $password, string $hash): bool;

    /**
     * Checks to see if the given hash is compatible with the validator
     */
    public function needsRehash(string $hash): bool;
}
