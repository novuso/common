<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Specification;

/**
 * Class CompositeSpecification
 */
abstract class CompositeSpecification implements Specification
{
    /**
     * @inheritDoc
     */
    abstract public function isSatisfiedBy(mixed $candidate): bool;

    /**
     * @inheritDoc
     */
    public function and(Specification $other): Specification
    {
        return new AndSpecification($this, $other);
    }

    /**
     * @inheritDoc
     */
    public function or(Specification $other): Specification
    {
        return new OrSpecification($this, $other);
    }

    /**
     * @inheritDoc
     */
    public function not(): Specification
    {
        return new NotSpecification($this);
    }
}
