<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Rule;

use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\System\Utility\Validate;

/**
 * Class CountRange
 */
class CountRange extends CompositeSpecification
{
    /**
     * Constructs CountRange
     */
    public function __construct(
        protected int $minCount,
        protected int $maxCount
    ) {
    }

    /**
     * @inheritDoc
     */
    public function isSatisfiedBy(mixed $candidate): bool
    {
        return Validate::rangeCount(
            $candidate,
            $this->minCount,
            $this->maxCount
        );
    }
}
