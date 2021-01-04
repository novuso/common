<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Specification;

use Novuso\Common\Application\Validation\ValidationContext;
use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\System\Exception\KeyException;
use Novuso\System\Utility\Assert;
use Novuso\System\Utility\Validate;

/**
 * Class SameFieldsSpecification
 */
final class SameFieldsSpecification extends CompositeSpecification
{
    /**
     * Constructs SameFieldsSpecification
     */
    public function __construct(
        protected string $fieldName1,
        protected string $fieldName2
    ) {
    }

    /**
     * Checks if the context satisfies the validation rule
     */
    public function isSatisfiedBy(mixed $context): bool
    {
        Assert::isInstanceOf($context, ValidationContext::class);

        try {
            return Validate::areSame(
                $context->get($this->fieldName1),
                $context->get($this->fieldName2)
            );
        } catch (KeyException $e) {
            return true;
        }
    }
}
