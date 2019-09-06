<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Specification;

/**
 * Class NotSpecification
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
