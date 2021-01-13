<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Rule;

use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\System\Utility\Validate;

/**
 * Class NumberMax
 */
class NumberMax extends CompositeSpecification
{
    /**
     * Constructs NumberMax
     */
    public function __construct(protected int|float $maxNumber)
    {
    }

    /**
     * @inheritDoc
     */
    public function isSatisfiedBy(mixed $candidate): bool
    {
        return Validate::maxNumber($candidate, $this->maxNumber);
    }
}
