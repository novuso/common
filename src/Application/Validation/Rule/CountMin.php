<?php declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Rule;

use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\System\Utility\Validate;

/**
 * Class CountMin
 */
class CountMin extends CompositeSpecification
{
    /**
     * Minimum count
     *
     * @var int
     */
    protected $minCount;

    /**
     * Constructs CountMin
     *
     * @param int $minCount The minimum count
     */
    public function __construct(int $minCount)
    {
        $this->minCount = $minCount;
    }

    /**
     * {@inheritdoc}
     */
    public function isSatisfiedBy($candidate): bool
    {
        return Validate::minCount($candidate, $this->minCount);
    }
}
