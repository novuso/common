<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Specification;

/**
 * Interface Specification
 */
interface Specification
{
    /**
     * Checks if a candidate satisfies the business rule
     */
    public function isSatisfiedBy(mixed $candidate): bool;

    /**
     * Creates a logical 'AND' with another specification
     */
    public function and(Specification $other): Specification;

    /**
     * Creates a logical 'OR' with another specification
     */
    public function or(Specification $other): Specification;

    /**
     * Creates a logical 'NOT' for this specification
     */
    public function not(): Specification;
}
