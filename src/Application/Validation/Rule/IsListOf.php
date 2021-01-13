<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Rule;

use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\System\Utility\Validate;

/**
 * Class IsListOf
 */
class IsListOf extends CompositeSpecification
{
    /**
     * Constructs IsListOf
     */
    public function __construct(protected string $type)
    {
    }

    /**
     * @inheritDoc
     */
    public function isSatisfiedBy(mixed $candidate): bool
    {
        return Validate::isListOf($candidate, $this->type);
    }
}
