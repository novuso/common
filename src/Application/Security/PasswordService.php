<?php declare(strict_types=1);

namespace Novuso\Common\Application\Security;

/**
 * Class PasswordService
 */
final class PasswordService implements PasswordHasher, PasswordValidator
{
    /**
     * Password hasher
     *
     * @var PasswordHasher
     */
    protected $hasher;

    /**
     * Password validator
     *
     * @var PasswordValidator
     */
    protected $validator;

    /**
     * Constructs PasswordService
     *
     * @param PasswordHasher    $hasher    The password hasher
     * @param PasswordValidator $validator The password validator
     */
    public function __construct(PasswordHasher $hasher, PasswordValidator $validator)
    {
        $this->hasher = $hasher;
        $this->validator = $validator;
    }

    /**
     * {@inheritdoc}
     */
    public function hash(string $password): string
    {
        return $this->hasher->hash($password);
    }

    /**
     * {@inheritdoc}
     */
    public function validate(string $password, string $hash): bool
    {
        return $this->validator->validate($password, $hash);
    }

    /**
     * {@inheritdoc}
     */
    public function needsRehash(string $hash): bool
    {
        return $this->validator->needsRehash($hash);
    }
}
