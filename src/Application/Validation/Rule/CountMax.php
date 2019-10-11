<?php declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Rule;

use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\System\Utility\Validate;

/**
 * Class CountMax
 */
class CountMax extends CompositeSpecification
{
    /**
     * Maximum count
     *
     * @var int
     */
    protected $maxCount;

    /**
     * Constructs CountMax
     *
     * @param int $maxCount The maximum count
     */
    public function __construct(int $maxCount)
    {
        $this->maxCount = $maxCount;
    }

    /**
     * {@inheritdoc}
     */
    public function isSatisfiedBy($candidate): bool
    {
        return Validate::maxCount($candidate, $this->maxCount);
    }
}
