<?php declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Rule;

use Novuso\Common\Domain\Specification\CompositeSpecification;

/**
 * Class IsTruthy
 */
class IsTruthy extends CompositeSpecification
{
    /**
     * {@inheritdoc}
     */
    public function isSatisfiedBy($candidate): bool
    {
        return !!$candidate;
    }
}
