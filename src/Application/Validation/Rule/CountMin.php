<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Rule;

use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\System\Utility\Validate;

/**
 * Class CountMin
 */
class CountMin extends CompositeSpecification
{
    /**
     * Constructs CountMin
     */
    public function __construct(protected int $minCount)
    {
    }

    /**
     * @inheritDoc
     */
    public function isSatisfiedBy(mixed $candidate): bool
    {
        return Validate::minCount($candidate, $this->minCount);
    }
}
