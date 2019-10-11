<?php declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Rule;

use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\System\Utility\Validate;

/**
 * Class LengthMin
 */
class LengthMin extends CompositeSpecification
{
    /**
     * Minimum length
     *
     * @var int
     */
    protected $minLength;

    /**
     * Constructs LengthMin
     *
     * @param int $minLength The minimum length
     */
    public function __construct(int $minLength)
    {
        $this->minLength = $minLength;
    }

    /**
     * {@inheritdoc}
     */
    public function isSatisfiedBy($candidate): bool
    {
        return Validate::minLength($candidate, $this->minLength);
    }
}
