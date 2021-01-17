<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
/**
 * Validation class
 */
class Validation
{
    /**
     * Constructs Validation
     */
    public function __construct(
        protected ?string $formName = null,
        protected array $rules
    ) {
    }

    /**
     * Retrieves the form name
     */
    public function formName(): ?string
    {
        return $this->formName;
    }

    /**
     * Retrieves the rules
     */
    public function rules(): array
    {
        return $this->rules;
    }
}
