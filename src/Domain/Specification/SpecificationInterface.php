<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Specification;

/**
 * SpecificationInterface is the interface for a business rule validation
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface SpecificationInterface
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
     * @param SpecificationInterface $other The other specification
     *
     * @return SpecificationInterface
     */
    public function and(SpecificationInterface $other): SpecificationInterface;

    /**
     * Creates a logical 'OR' with another specification
     *
     * @param SpecificationInterface $other The other specification
     *
     * @return SpecificationInterface
     */
    public function or(SpecificationInterface $other): SpecificationInterface;

    /**
     * Creates a logical 'NOT' for this specification
     *
     * @return SpecificationInterface
     */
    public function not(): SpecificationInterface;
}
