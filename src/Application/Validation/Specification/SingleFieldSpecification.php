<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Specification;

use Novuso\Common\Application\Validation\ValidationContext;
use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\Common\Domain\Specification\Specification;
use Novuso\System\Exception\KeyException;
use Novuso\System\Utility\Assert;

/**
 * Class SingleFieldSpecification
 */
final class SingleFieldSpecification extends CompositeSpecification
{
    /**
     * Constructs SingleFieldSpecification
     */
    public function __construct(
        protected string $fieldName,
        protected Specification $rule
    ) {
    }

    /**
     * Checks if the context satisfies the validation rule
     */
    public function isSatisfiedBy(mixed $context): bool
    {
        Assert::isInstanceOf($context, ValidationContext::class);

        try {
            return $this->rule->isSatisfiedBy($context->get($this->fieldName));
        } catch (KeyException $e) {
            return true;
        }
    }
}
