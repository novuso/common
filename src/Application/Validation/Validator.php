<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Validation;

/**
 * Interface Validator
 */
interface Validator
{
    /**
     * Validates the context
     *
     * This method should return true if validation passes; false otherwise.
     *
     * Optionally, this method may add errors to the context when validation
     * fails.
     */
    public function validate(ValidationContext $context): bool;
}
