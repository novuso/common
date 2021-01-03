<?php

declare(strict_types=1);

namespace Novuso\Common\Domain\Specification;

/**
 * Class OrSpecification
 */
final class OrSpecification extends CompositeSpecification
{
    /**
     * Constructs OrSpecification
     */
    public function __construct(
        protected Specification $firstSpec,
        protected Specification $secondSpec
    ) {
    }

    /**
     * @inheritDoc
     */
    public function isSatisfiedBy(mixed $candidate): bool
    {
        return $this->firstSpec->isSatisfiedBy($candidate)
            || $this->secondSpec->isSatisfiedBy($candidate);
    }
}
