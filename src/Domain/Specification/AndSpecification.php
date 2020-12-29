<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Specification;

/**
 * Class AndSpecification
 */
final class AndSpecification extends CompositeSpecification
{
    /**
     * Constructs AndSpecification
     */
    public function __construct(protected Specification $firstSpec, protected Specification $secondSpec) {}

    /**
     * @inheritDoc
     */
    public function isSatisfiedBy(mixed $candidate): bool
    {
        return $this->firstSpec->isSatisfiedBy($candidate)
            && $this->secondSpec->isSatisfiedBy($candidate);
    }
}
