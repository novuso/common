<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Rule;

use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\System\Utility\Validate;

/**
 * Class StringEndsWith
 */
class StringEndsWith extends CompositeSpecification
{
    /**
     * Constructs StringEndsWith
     */
    public function __construct(protected string $search)
    {
    }

    /**
     * @inheritDoc
     */
    public function isSatisfiedBy(mixed $candidate): bool
    {
        return Validate::endsWith($candidate, $this->search);
    }
}
