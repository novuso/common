<?php declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Rule;

use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\System\Utility\Validate;

/**
 * Class NumberRange
 */
class NumberRange extends CompositeSpecification
{
    /**
     * Minimum number
     *
     * @var int|float
     */
    protected $minNumber;

    /**
     * Maximum number
     *
     * @var int|float
     */
    protected $maxNumber;

    /**
     * Constructs NumberRange
     *
     * @param int|float $minNumber The minimum number
     * @param int|float $maxNumber The maximum number
     */
    public function __construct($minNumber, $maxNumber)
    {
        $this->minNumber = $minNumber;
        $this->maxNumber = $maxNumber;
    }

    /**
     * {@inheritdoc}
     */
    public function isSatisfiedBy($candidate): bool
    {
        return Validate::rangeNumber($candidate, $this->minNumber, $this->maxNumber);
    }
}
