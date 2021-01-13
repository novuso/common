<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Rule;

use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\System\Utility\Validate;

/**
 * Class KeyIsset
 */
class KeyIsset extends CompositeSpecification
{
    /**
     * Constructs KeyIsset
     */
    public function __construct(protected string $key)
    {
    }

    /**
     * @inheritDoc
     */
    public function isSatisfiedBy(mixed $candidate): bool
    {
        return Validate::keyIsset($candidate, $this->key);
    }
}
