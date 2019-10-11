<?php declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Rule;

use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\System\Utility\Validate;

/**
 * Class NumberMax
 */
class NumberMax extends CompositeSpecification
{
    /**
     * Maximum number
     *
     * @var int|float
     */
    protected $maxNumber;

    /**
     * Constructs NumberMax
     *
     * @param int|float $maxNumber The maximum number
     */
    public function __construct($maxNumber)
    {
        $this->maxNumber = $maxNumber;
    }

    /**
     * {@inheritdoc}
     */
    public function isSatisfiedBy($candidate): bool
    {
        return Validate::maxNumber($candidate, $this->maxNumber);
    }
}
