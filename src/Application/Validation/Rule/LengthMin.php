<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Rule;

use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\System\Utility\Validate;

/**
 * Class LengthMin
 */
class LengthMin extends CompositeSpecification
{
    /**
     * Constructs LengthMin
     */
    public function __construct(protected int $minLength)
    {
    }

    /**
     * @inheritDoc
     */
    public function isSatisfiedBy(mixed $candidate): bool
    {
        return Validate::minLength($candidate, $this->minLength);
    }
}
