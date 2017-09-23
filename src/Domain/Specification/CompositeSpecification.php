<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Specification;

/**
 * CompositeSpecification is the base class for composite specifications
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
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
