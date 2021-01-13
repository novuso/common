<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Rule;

use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\System\Utility\Validate;

/**
 * Class CountMax
 */
class CountMax extends CompositeSpecification
{
    /**
     * Constructs CountMax
     */
    public function __construct(protected int $maxCount)
    {
    }

    /**
     * @inheritDoc
     */
    public function isSatisfiedBy(mixed $candidate): bool
    {
        return Validate::maxCount($candidate, $this->maxCount);
    }
}
