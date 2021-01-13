<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Rule;

use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\System\Utility\Validate;

/**
 * Class KeyNotEmpty
 */
class KeyNotEmpty extends CompositeSpecification
{
    /**
     * Constructs KeyNotEmpty
     */
    public function __construct(protected string $key)
    {
    }

    /**
     * @inheritDoc
     */
    public function isSatisfiedBy(mixed $candidate): bool
    {
        return Validate::keyNotEmpty($candidate, $this->key);
    }
}
