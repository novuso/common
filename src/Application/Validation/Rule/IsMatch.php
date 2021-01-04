<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Rule;

use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\System\Utility\Validate;

/**
 * Class IsMatch
 */
class IsMatch extends CompositeSpecification
{
    /**
     * Constructs IsMatch
     */
    public function __construct(protected string $pattern)
    {
    }

    /**
     * @inheritDoc
     */
    public function isSatisfiedBy(mixed $candidate): bool
    {
        return Validate::isMatch($candidate, $this->pattern);
    }
}
