<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Rule;

use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\System\Utility\Validate;

/**
 * Class LengthMax
 */
class LengthMax extends CompositeSpecification
{
    /**
     * Constructs LengthMax
     */
    public function __construct(protected int $maxLength)
    {
    }

    /**
     * @inheritDoc
     */
    public function isSatisfiedBy(mixed $candidate): bool
    {
        return Validate::maxLength($candidate, $this->maxLength);
    }
}
