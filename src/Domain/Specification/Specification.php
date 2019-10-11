<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Specification;

/**
 * Interface Specification
 */
interface Specification
{
    /**
     * Checks if a candidate satisfies the business rule
     *
     * @param mixed $candidate The candidate object
     *
     * @return bool
     */
    public function isSatisfiedBy($candidate): bool;

    /**
     * Creates a logical 'AND' with another specification
     *
     * @param Specification $other The other specification
     *
     * @return Specification
     */
    public function and(Specification $other): Specification;

    /**
     * Creates a logical 'OR' with another specification
     *
     * @param Specification $other The other specification
     *
     * @return Specification
     */
    public function or(Specification $other): Specification;

    /**
     * Creates a logical 'NOT' for this specification
     *
     * @return Specification
     */
    public function not(): Specification;
}
