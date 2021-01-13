<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Rule;

use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\System\Utility\Validate;

/**
 * Class LengthRange
 */
class LengthRange extends CompositeSpecification
{
    /**
     * Constructs LengthRange
     */
    public function __construct(
        protected int $minLength,
        protected int $maxLength
    ) {
    }

    /**
     * @inheritDoc
     */
    public function isSatisfiedBy(mixed $candidate): bool
    {
        return Validate::rangeLength(
            $candidate,
            $this->minLength,
            $this->maxLength
        );
    }
}
