<?php declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Rule;

use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\System\Utility\Validate;

/**
 * Class IsListOf
 */
class IsListOf extends CompositeSpecification
{
    /**
     * Type
     *
     * @var string
     */
    protected $type;

    /**
     * Constructs IsListOf
     *
     * @param string $type The type
     */
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * {@inheritdoc}
     */
    public function isSatisfiedBy($candidate): bool
    {
        return Validate::isListOf($candidate, $this->type);
    }
}
