<?php declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Rule;

use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\System\Utility\Validate;

/**
 * Class CountRange
 */
class CountRange extends CompositeSpecification
{
    /**
     * Minimum count
     *
     * @var int
     */
    protected $minCount;

    /**
     * Maximum count
     *
     * @var int
     */
    protected $maxCount;

    /**
     * Constructs CountRange
     *
     * @param int $minCount The minimum count
     * @param int $maxCount The maximum count
     */
    public function __construct(int $minCount, int $maxCount)
    {
        $this->minCount = $minCount;
        $this->maxCount = $maxCount;
    }

    /**
     * {@inheritdoc}
     */
    public function isSatisfiedBy($candidate): bool
    {
        return Validate::rangeCount($candidate, $this->minCount, $this->maxCount);
    }
}
