<?php declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Specification;

use Novuso\Common\Application\Validation\ValidationContext;
use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\Common\Domain\Specification\Specification;
use Novuso\System\Exception\KeyException;

/**
 * Class SingleFieldSpecification
 */
final class SingleFieldSpecification extends CompositeSpecification
{
    /**
     * Field name
     *
     * @var string
     */
    protected $fieldName;

    /**
     * Validation rule
     *
     * @var Specification
     */
    protected $rule;

    /**
     * Constructs SingleFieldSpecification
     *
     * @param string        $fieldName The field name
     * @param Specification $rule      The validation rule
     */
    public function __construct(string $fieldName, Specification $rule)
    {
        $this->fieldName = $fieldName;
        $this->rule = $rule;
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
            return $this->rule->isSatisfiedBy($context->get($this->fieldName));
        } catch (KeyException $e) {
            return true;
        }
    }
}
