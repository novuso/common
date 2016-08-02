<?php

namespace Novuso\Test\Common\Resources\Domain\Specification;

use Novuso\Common\Domain\Specification\CompositeSpecification;

class UsernameIsAlphaOnly extends CompositeSpecification
{
    public function isSatisfiedBy($candidate): bool
    {
        if (!($candidate instanceof Username)) {
            return false;
        }

        if (!preg_match('/\A[a-z]*\z/ui', $candidate->toString())) {
            return false;
        }

        return true;
    }
}
