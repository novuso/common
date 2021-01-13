<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Rule;

use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\System\Utility\Validate;

/**
 * Class NumberExact
 */
class NumberExact extends CompositeSpecification
{
    /**
     * Constructs NumberExact
     */
    public function __construct(protected int|float $number)
    {
    }

    /**
     * @inheritDoc
     */
    public function isSatisfiedBy(mixed $candidate): bool
    {
        return Validate::exactNumber($candidate, $this->number);
    }
}
