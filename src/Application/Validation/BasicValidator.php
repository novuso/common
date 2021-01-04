<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Validation;

use Novuso\Common\Domain\Specification\Specification;

/**
 * Class BasicValidator
 */
final class BasicValidator implements Validator
{
    /**
     * Constructs BasicValidator
     */
    public function __construct(
        protected Specification $specification,
        protected string $fieldName,
        protected string $errorMessage
    ) {
    }

    /**
     * @inheritDoc
     */
    public function validate(ValidationContext $context): bool
    {
        if (!$this->specification->isSatisfiedBy($context)) {
            $context->addError($this->fieldName, $this->errorMessage);

            return false;
        }

        return true;
    }
}
