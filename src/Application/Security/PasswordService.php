<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Security;

/**
 * Class PasswordService
 */
final class PasswordService implements PasswordHasher, PasswordValidator
{
    /**
     * Constructs PasswordService
     */
    public function __construct(
        protected PasswordHasher $hasher,
        protected PasswordValidator $validator
    ) {
    }

    /**
     * @inheritDoc
     */
    public function hash(string $password): string
    {
        return $this->hasher->hash($password);
    }

    /**
     * @inheritDoc
     */
    public function validate(string $password, string $hash): bool
    {
        return $this->validator->validate($password, $hash);
    }

    /**
     * @inheritDoc
     */
    public function needsRehash(string $hash): bool
    {
        return $this->validator->needsRehash($hash);
    }
}
