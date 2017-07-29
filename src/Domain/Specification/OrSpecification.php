<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Specification;

/**
 * OrSpecification is a logical 'OR' composed of two specifications
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class OrSpecification extends CompositeSpecification
{
    /**
     * First specification
     *
     * @var SpecificationInterface
     */
    protected $firstSpec;

    /**
     * Second specification
     *
     * @var SpecificationInterface
     */
    protected $secondSpec;

    /**
     * Constructs OrSpecification
     *
     * @param SpecificationInterface $firstSpec  The first specification
     * @param SpecificationInterface $secondSpec The second specification
     */
    public function __construct(SpecificationInterface $firstSpec, SpecificationInterface $secondSpec)
    {
        $this->firstSpec = $firstSpec;
        $this->secondSpec = $secondSpec;
    }

    /**
     * {@inheritdoc}
     */
    public function isSatisfiedBy($candidate): bool
    {
        return $this->firstSpec->isSatisfiedBy($candidate) || $this->secondSpec->isSatisfiedBy($candidate);
    }
}
