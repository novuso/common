<?php declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Rule;

use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\System\Utility\Validate;

/**
 * Class LengthRange
 */
class LengthRange extends CompositeSpecification
{
    /**
     * Minimum length
     *
     * @var int
     */
    protected $minLength;

    /**
     * Maximum length
     *
     * @var int
     */
    protected $maxLength;

    /**
     * Constructs LengthRange
     *
     * @param int $minLength The minimum length
     * @param int $maxLength The maximum length
     */
    public function __construct(int $minLength, int $maxLength)
    {
        $this->minLength = $minLength;
        $this->maxLength = $maxLength;
    }

    /**
     * {@inheritdoc}
     */
    public function isSatisfiedBy($candidate): bool
    {
        return Validate::rangeLength($candidate, $this->minLength, $this->maxLength);
    }
}
