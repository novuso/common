<?php declare(strict_types=1);

namespace Novuso\Common\Test\Resources\Domain\Specification;

use Novuso\Common\Domain\Specification\CompositeSpecification;

/**
 * Class UsernameIsAlphaOnly
 */
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
