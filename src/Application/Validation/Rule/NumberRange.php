<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Rule;

use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\System\Utility\Validate;

/**
 * Class NumberRange
 */
class NumberRange extends CompositeSpecification
{
    /**
     * Constructs NumberRange
     */
    public function __construct(
        protected int|float $minNumber,
        protected int|float $maxNumber
    ) {
    }

    /**
     * @inheritDoc
     */
    public function isSatisfiedBy(mixed $candidate): bool
    {
        return Validate::rangeNumber(
            $candidate,
            $this->minNumber,
            $this->maxNumber
        );
    }
}
