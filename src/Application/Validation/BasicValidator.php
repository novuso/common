<?php declare(strict_types=1);

namespace Novuso\Common\Application\Validation;

use Novuso\Common\Domain\Specification\Specification;

/**
 * Class BasicValidator
 */
final class BasicValidator implements Validator
{
    /**
     * Validating specification
     *
     * @var Specification
     */
    protected $specification;

    /**
     * Field name
     *
     * @var string
     */
    protected $fieldName;

    /**
     * Error message
     *
     * @var string
     */
    protected $errorMessage;

    /**
     * Constructs BasicValidator
     *
     * @param Specification $specification The validating specification
     * @param string        $fieldName     The field name
     * @param string        $errorMessage  The error message
     */
    public function __construct(Specification $specification, string $fieldName, string $errorMessage)
    {
        $this->fieldName = $fieldName;
        $this->specification = $specification;
        $this->errorMessage = $errorMessage;
    }

    /**
     * {@inheritdoc}
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
