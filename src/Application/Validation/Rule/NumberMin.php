<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Rule;

use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\System\Utility\Validate;

/**
 * Class NumberMin
 */
class NumberMin extends CompositeSpecification
{
    /**
     * Constructs NumberMin
     */
    public function __construct(protected int|float $minNumber)
    {
    }

    /**
     * @inheritDoc
     */
    public function isSatisfiedBy(mixed $candidate): bool
    {
        return Validate::minNumber($candidate, $this->minNumber);
    }
}
