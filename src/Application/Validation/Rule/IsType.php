<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Rule;

use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\System\Utility\Validate;

/**
 * Class IsType
 */
class IsType extends CompositeSpecification
{
    protected bool $nullable;

    /**
     * Constructs IsType
     */
    public function __construct(protected string $type)
    {
        $this->nullable = false;
        if (str_starts_with($this->type, '?')) {
            $this->nullable = true;
            $this->type = substr($this->type, 1);
        }
    }

    /**
     * @inheritDoc
     */
    public function isSatisfiedBy(mixed $candidate): bool
    {
        if ($this->nullable && $candidate === null) {
            return true;
        }

        return Validate::isType($candidate, $this->type);
    }
}
