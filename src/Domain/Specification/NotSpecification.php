<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Specification;

/**
 * NotSpecification is a logical 'NOT' for a specification
 *
 * @copyright Copyright (c) 2016, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class NotSpecification extends CompositeSpecification
{
    /**
     * Specification
     *
     * @var Specification
     */
    protected $spec;

    /**
     * Constructs NotSpecification
     *
     * @param Specification $spec The specification
     */
    public function __construct(Specification $spec)
    {
        $this->spec = $spec;
    }

    /**
     * {@inheritdoc}
     */
    public function isSatisfiedBy($candidate): bool
    {
        return !$this->spec->isSatisfiedBy($candidate);
    }
}
