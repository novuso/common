<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Specification;

/**
 * Class CompositeSpecification
 */
abstract class CompositeSpecification implements Specification
{
    /**
     * {@inheritdoc}
     */
    abstract public function isSatisfiedBy($candidate): bool;

    /**
     * {@inheritdoc}
     */
    public function and(Specification $other): Specification
    {
        return new AndSpecification($this, $other);
    }

    /**
     * {@inheritdoc}
     */
    public function or(Specification $other): Specification
    {
        return new OrSpecification($this, $other);
    }

    /**
     * {@inheritdoc}
     */
    public function not(): Specification
    {
        return new NotSpecification($this);
    }
}
