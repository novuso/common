<?php declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Specification;

use Novuso\Common\Application\Validation\ValidationContext;
use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\System\Exception\KeyException;

/**
 * Class RequiredFieldSpecification
 */
final class RequiredFieldSpecification extends CompositeSpecification
{
    /**
     * Field name
     *
     * @var string
     */
    protected $fieldName;

    /**
     * Constructs RequiredFieldSpecification
     *
     * @param string $fieldName The field name
     */
    public function __construct(string $fieldName)
    {
        $this->fieldName = $fieldName;
    }

    /**
     * Checks if the context satisfies the validation rule
     *
     * @param ValidationContext $context The context
     *
     * @return bool
     */
    public function isSatisfiedBy($context): bool
    {
        try {
            $context->get($this->fieldName);

            return true;
        } catch (KeyException $e) {
            return false;
        }
    }
}
