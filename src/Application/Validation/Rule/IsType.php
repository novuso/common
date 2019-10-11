<?php declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Rule;

use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\System\Utility\Validate;

/**
 * Class IsType
 */
class IsType extends CompositeSpecification
{
    /**
     * Type
     *
     * @var string
     */
    protected $type;

    /**
     * Whether the type is nullable
     *
     * @var bool
     */
    protected $nullable;

    /**
     * Constructs IsType
     *
     * @param string $type The type
     */
    public function __construct(string $type)
    {
        $this->nullable = false;
        if (strpos($type, '?') === 0) {
            $this->nullable = true;
        }

        $this->type = $type;
    }

    /**
     * {@inheritdoc}
     */
    public function isSatisfiedBy($candidate): bool
    {
        if ($this->nullable && $candidate === null) {
            return true;
        }

        return Validate::isType($candidate, $this->type);
    }
}
