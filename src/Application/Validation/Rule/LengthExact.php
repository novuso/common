<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Rule;

use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\System\Utility\Validate;

/**
 * Class LengthExact
 */
class LengthExact extends CompositeSpecification
{
    /**
     * Constructs LengthExact
     */
    public function __construct(protected int $length)
    {
    }

    /**
     * @inheritDoc
     */
    public function isSatisfiedBy(mixed $candidate): bool
    {
        return Validate::exactLength($candidate, $this->length);
    }
}
