<?php

namespace Novuso\Test\Common\Resources\Domain\Specification;

use Novuso\Common\Domain\Specification\CompositeSpecification;

class UsernameIsUnique extends CompositeSpecification
{
    protected $usernames = [
        'johnnickell',
        'leeroyjenkins',
        'joesmith',
        'admin123'
    ];

    public function isSatisfiedBy($candidate): bool
    {
        if (!($candidate instanceof Username)) {
            return false;
        }

        if (in_array($candidate->toString(), $this->usernames)) {
            return false;
        }

        return true;
    }
}
