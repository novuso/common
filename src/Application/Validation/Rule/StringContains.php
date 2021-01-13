<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Rule;

use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\System\Utility\Validate;

/**
 * Class StringContains
 */
class StringContains extends CompositeSpecification
{
    /**
     * Constructs StringContains
     */
    public function __construct(protected string $search)
    {
    }

    /**
     * @inheritDoc
     */
    public function isSatisfiedBy(mixed $candidate): bool
    {
        return Validate::contains($candidate, $this->search);
    }
}
