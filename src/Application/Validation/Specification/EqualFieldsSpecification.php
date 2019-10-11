<?php declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Specification;

use Novuso\Common\Application\Validation\ValidationContext;
use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\System\Exception\KeyException;
use Novuso\System\Utility\Validate;

/**
 * Class EqualFieldsSpecification
 */
final class EqualFieldsSpecification extends CompositeSpecification
{
    /**
     * First field name
     *
     * @var string
     */
    protected $fieldName1;

    /**
     * Second field name
     *
     * @var string
     */
    protected $fieldName2;

    /**
     * Constructs EqualFieldsSpecification
     *
     * @param string $fieldName1 The first field name
     * @param string $fieldName2 The second field name
     */
    public function __construct(string $fieldName1, string $fieldName2)
    {
        $this->fieldName1 = $fieldName1;
        $this->fieldName2 = $fieldName2;
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
            return Validate::areEqual(
                $context->get($this->fieldName1),
                $context->get($this->fieldName2)
            );
        } catch (KeyException $e) {
            return true;
        }
    }
}
