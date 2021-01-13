<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Rule;

use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\System\Utility\Validate;

/**
 * Class InList
 */
class InList extends CompositeSpecification
{
    /**
     * Constructs InList
     */
    public function __construct(protected array $list)
    {
    }

    /**
     * @inheritDoc
     */
    public function isSatisfiedBy(mixed $candidate): bool
    {
        return Validate::isOneOf($candidate, $this->list);
    }
}
