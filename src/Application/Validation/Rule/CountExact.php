<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Rule;

use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\System\Utility\Validate;

/**
 * Class CountExact
 */
class CountExact extends CompositeSpecification
{
    /**
     * Constructs CountExact
     */
    public function __construct(protected int $count)
    {
    }

    /**
     * @inheritDoc
     */
    public function isSatisfiedBy(mixed $candidate): bool
    {
        return Validate::exactCount($candidate, $this->count);
    }
}
