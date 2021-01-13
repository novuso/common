<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Specification;

use Novuso\Common\Application\Validation\ValidationContext;
use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\System\Exception\KeyException;
use Novuso\System\Utility\Assert;

/**
 * Class RequiredFieldSpecification
 */
final class RequiredFieldSpecification extends CompositeSpecification
{
    /**
     * Constructs RequiredFieldSpecification
     */
    public function __construct(protected string $fieldName)
    {
    }

    /**
     * Checks if the context satisfies the validation rule
     */
    public function isSatisfiedBy(mixed $context): bool
    {
        Assert::isInstanceOf($context, ValidationContext::class);

        try {
            $context->get($this->fieldName);

            return true;
        } catch (KeyException $e) {
            return false;
        }
    }
}
